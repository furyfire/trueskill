<?php

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\FactorGraphs\ScheduleLoop;
use DNW\Skills\FactorGraphs\ScheduleSequence;
use DNW\Skills\FactorGraphs\ScheduleStep;
use DNW\Skills\TrueSkill\TrueSkillFactorGraph;
use Exception;

// The whole purpose of this is to do a loop on the bottom
class IteratedTeamDifferencesInnerLayer extends TrueSkillFactorGraphLayer
{
    public function __construct(
        TrueSkillFactorGraph $parentGraph,
        private readonly TeamPerformancesToTeamPerformanceDifferencesLayer $_TeamPerformancesToTeamPerformanceDifferencesLayer,
        private readonly TeamDifferencesComparisonLayer $_TeamDifferencesComparisonLayer
    ) {
        parent::__construct($parentGraph);
    }

    public function getLocalFactors(): array
    {
        return array_merge($this->_TeamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors(),
            $this->_TeamDifferencesComparisonLayer->getLocalFactors()
        );
    }

    public function buildLayer()
    {
        $inputVariablesGroups = $this->getInputVariablesGroups();
        $this->_TeamPerformancesToTeamPerformanceDifferencesLayer->setInputVariablesGroups($inputVariablesGroups);
        $this->_TeamPerformancesToTeamPerformanceDifferencesLayer->buildLayer();

        $teamDifferencesOutputVariablesGroups = $this->_TeamPerformancesToTeamPerformanceDifferencesLayer->getOutputVariablesGroups();
        $this->_TeamDifferencesComparisonLayer->setInputVariablesGroups($teamDifferencesOutputVariablesGroups);
        $this->_TeamDifferencesComparisonLayer->buildLayer();
    }

    public function createPriorSchedule(): ScheduleSequence
    {
        switch (is_countable($this->getInputVariablesGroups()) ? count($this->getInputVariablesGroups()) : 0) {
            case 0:
            case 1:
                throw new Exception('InvalidOperation');
            case 2:
                $loop = $this->createTwoTeamInnerPriorLoopSchedule();
                break;
            default:
                $loop = $this->createMultipleTeamInnerPriorLoopSchedule();
                break;
        }

        // When dealing with differences, there are always (n-1) differences, so add in the 1
        $totalTeamDifferences = is_countable($this->_TeamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors()) ? count($this->_TeamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors()) : 0;

        $localFactors = $this->_TeamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors();

        $firstDifferencesFactor = $localFactors[0];
        $lastDifferencesFactor = $localFactors[$totalTeamDifferences - 1];

        return new ScheduleSequence(
            'inner schedule',
            [
                $loop,
                new ScheduleStep(
                    'teamPerformanceToPerformanceDifferenceFactors[0] @ 1',
                    $firstDifferencesFactor, 1),
                new ScheduleStep(
                    sprintf('teamPerformanceToPerformanceDifferenceFactors[teamTeamDifferences = %d - 1] @ 2', $totalTeamDifferences),
                    $lastDifferencesFactor, 2),
            ]
        );
    }

    private function createTwoTeamInnerPriorLoopSchedule()
    {
        $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors = $this->_TeamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors();
        $teamDifferencesComparisonLayerLocalFactors = $this->_TeamDifferencesComparisonLayer->getLocalFactors();

        $firstPerfToTeamDiff = $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors[0];
        $firstTeamDiffComparison = $teamDifferencesComparisonLayerLocalFactors[0];
        $itemsToSequence = [
            new ScheduleStep(
                'send team perf to perf differences',
                $firstPerfToTeamDiff,
                0),
            new ScheduleStep(
                'send to greater than or within factor',
                $firstTeamDiffComparison,
                0),
        ];

        return $this->scheduleSequence(
            $itemsToSequence,
            'loop of just two teams inner sequence');
    }

    private function createMultipleTeamInnerPriorLoopSchedule()
    {
        $totalTeamDifferences = is_countable($this->_TeamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors()) ? count($this->_TeamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors()) : 0;

        $forwardScheduleList = [];

        for ($i = 0; $i < $totalTeamDifferences - 1; $i++) {
            $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors = $this->_TeamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors();
            $teamDifferencesComparisonLayerLocalFactors = $this->_TeamDifferencesComparisonLayer->getLocalFactors();

            $currentTeamPerfToTeamPerfDiff = $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors[$i];
            $currentTeamDiffComparison = $teamDifferencesComparisonLayerLocalFactors[$i];

            $currentForwardSchedulePiece =
                $this->scheduleSequence(
                    [
                        new ScheduleStep(
                            sprintf('team perf to perf diff %d', $i),
                            $currentTeamPerfToTeamPerfDiff, 0),
                        new ScheduleStep(
                            sprintf('greater than or within result factor %d', $i),
                            $currentTeamDiffComparison, 0),
                        new ScheduleStep(
                            sprintf('team perf to perf diff factors [%d], 2', $i),
                            $currentTeamPerfToTeamPerfDiff, 2),
                    ], sprintf('current forward schedule piece %d', $i));

            $forwardScheduleList[] = $currentForwardSchedulePiece;
        }

        $forwardSchedule = new ScheduleSequence('forward schedule', $forwardScheduleList);

        $backwardScheduleList = [];

        for ($i = 0; $i < $totalTeamDifferences - 1; $i++) {
            $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors = $this->_TeamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors();
            $teamDifferencesComparisonLayerLocalFactors = $this->_TeamDifferencesComparisonLayer->getLocalFactors();

            $differencesFactor = $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors[$totalTeamDifferences - 1 - $i];
            $comparisonFactor = $teamDifferencesComparisonLayerLocalFactors[$totalTeamDifferences - 1 - $i];
            $performancesToDifferencesFactor = $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors[$totalTeamDifferences - 1 - $i];

            $currentBackwardSchedulePiece = new ScheduleSequence(
                'current backward schedule piece',
                [
                    new ScheduleStep(
                        sprintf('teamPerformanceToPerformanceDifferenceFactors[totalTeamDifferences - 1 - %d] @ 0', $i),
                        $differencesFactor, 0
                    ),
                    new ScheduleStep(
                        sprintf('greaterThanOrWithinResultFactors[totalTeamDifferences - 1 - %d] @ 0', $i),
                        $comparisonFactor, 0
                    ),
                    new ScheduleStep(
                        sprintf('teamPerformanceToPerformanceDifferenceFactors[totalTeamDifferences - 1 - %d] @ 1', $i),
                        $performancesToDifferencesFactor, 1
                    ),
                ]);
            $backwardScheduleList[] = $currentBackwardSchedulePiece;
        }

        $backwardSchedule = new ScheduleSequence('backward schedule', $backwardScheduleList);

        $forwardBackwardScheduleToLoop =
            new ScheduleSequence(
                'forward Backward Schedule To Loop',
                [$forwardSchedule, $backwardSchedule]
            );

        $initialMaxDelta = 0.0001;

        return new ScheduleLoop(
            sprintf('loop with max delta of %f', $initialMaxDelta),
            $forwardBackwardScheduleToLoop,
            $initialMaxDelta
        );
    }
}
