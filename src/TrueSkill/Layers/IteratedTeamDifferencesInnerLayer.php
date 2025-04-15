<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\FactorGraphs\ScheduleLoop;
use DNW\Skills\FactorGraphs\ScheduleSequence;
use DNW\Skills\FactorGraphs\ScheduleStep;
use DNW\Skills\TrueSkill\TrueSkillFactorGraph;
use Exception;

// The whole purpose of this is to do a loop on the bottom
final class IteratedTeamDifferencesInnerLayer extends TrueSkillFactorGraphLayer
{
    public function __construct(
        TrueSkillFactorGraph $parentGraph,
        private readonly TeamPerformancesToTeamPerformanceDifferencesLayer $teamPerformancesToTeamPerformanceDifferencesLayer,
        private readonly TeamDifferencesComparisonLayer $teamDifferencesComparisonLayer
    )
    {
        parent::__construct($parentGraph);
    }

    #[\Override]
    public function getLocalFactors(): array
    {
        return array_merge(
            $this->teamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors(),
            $this->teamDifferencesComparisonLayer->getLocalFactors()
        );
    }

    #[\Override]
    public function buildLayer(): void
    {
        $inputVariablesGroups = $this->getInputVariablesGroups();
        $this->teamPerformancesToTeamPerformanceDifferencesLayer->setInputVariablesGroups($inputVariablesGroups);
        $this->teamPerformancesToTeamPerformanceDifferencesLayer->buildLayer();

        $teamDifferencesOutputVariablesGroups = $this->teamPerformancesToTeamPerformanceDifferencesLayer->getOutputVariablesGroups();
        $this->teamDifferencesComparisonLayer->setInputVariablesGroups($teamDifferencesOutputVariablesGroups);
        $this->teamDifferencesComparisonLayer->buildLayer();
    }

    #[\Override]
    public function createPriorSchedule(): ?ScheduleSequence
    {
        switch (count($this->getInputVariablesGroups())) {
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
        $totalTeamDifferences = count($this->teamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors());

        $localFactors = $this->teamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors();

        $firstDifferencesFactor = $localFactors[0];
        $lastDifferencesFactor = $localFactors[$totalTeamDifferences - 1];

        //inner schedule
        return new ScheduleSequence(
            [
                $loop,
                //teamPerformanceToPerformanceDifferenceFactors[0] @ 1
                new ScheduleStep(
                    $firstDifferencesFactor,
                    1
                ),
                //teamPerformanceToPerformanceDifferenceFactors[teamTeamDifferences = %d - 1] @ 2
                new ScheduleStep(
                    $lastDifferencesFactor,
                    2
                ),
            ]
        );
    }

    private function createTwoTeamInnerPriorLoopSchedule(): ScheduleSequence
    {
        $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors = $this->teamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors();
        $teamDifferencesComparisonLayerLocalFactors = $this->teamDifferencesComparisonLayer->getLocalFactors();

        $firstPerfToTeamDiff = $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors[0];
        $firstTeamDiffComparison = $teamDifferencesComparisonLayerLocalFactors[0];
        $itemsToSequence = [
            //send team perf to perf differences
            new ScheduleStep(
                $firstPerfToTeamDiff,
                0
            ),
            //send to greater than or within factor
            new ScheduleStep(
                $firstTeamDiffComparison,
                0
            ),
        ];

        //loop of just two teams inner sequence
        return $this->scheduleSequence(
            $itemsToSequence
        );
    }

    private function createMultipleTeamInnerPriorLoopSchedule(): ScheduleLoop
    {
        $totalTeamDifferences = count($this->teamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors());

        $forwardScheduleList = [];

        for ($i = 0; $i < $totalTeamDifferences - 1; ++$i) {
            $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors = $this->teamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors();
            $teamDifferencesComparisonLayerLocalFactors = $this->teamDifferencesComparisonLayer->getLocalFactors();

            $currentTeamPerfToTeamPerfDiff = $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors[$i];
            $currentTeamDiffComparison = $teamDifferencesComparisonLayerLocalFactors[$i];

            //current forward schedule piece $i
            $currentForwardSchedulePiece =
                $this->scheduleSequence(
                    [
                        //team perf to perf diff
                        new ScheduleStep(
                            $currentTeamPerfToTeamPerfDiff,
                            0
                        ),
                        //greater than or within result factor
                        new ScheduleStep(
                            $currentTeamDiffComparison,
                            0
                        ),
                        //'team perf to perf diff factors
                        new ScheduleStep(
                            $currentTeamPerfToTeamPerfDiff,
                            2
                        ),
                    ]
                );

            $forwardScheduleList[] = $currentForwardSchedulePiece;
        }

        //forward schedule
        $forwardSchedule = new ScheduleSequence($forwardScheduleList);

        $backwardScheduleList = [];

        for ($i = 0; $i < $totalTeamDifferences - 1; ++$i) {
            $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors = $this->teamPerformancesToTeamPerformanceDifferencesLayer->getLocalFactors();
            $teamDifferencesComparisonLayerLocalFactors = $this->teamDifferencesComparisonLayer->getLocalFactors();

            $differencesFactor = $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors[$totalTeamDifferences - 1 - $i];
            $comparisonFactor = $teamDifferencesComparisonLayerLocalFactors[$totalTeamDifferences - 1 - $i];
            $performancesToDifferencesFactor = $teamPerformancesToTeamPerformanceDifferencesLayerLocalFactors[$totalTeamDifferences - 1 - $i];

            //current backward schedule piece
            $currentBackwardSchedulePiece = new ScheduleSequence(
                [
                    //teamPerformanceToPerformanceDifferenceFactors[totalTeamDifferences - 1 - %d] @ 0
                    new ScheduleStep(
                        $differencesFactor,
                        0
                    ),
                    //greaterThanOrWithinResultFactors[totalTeamDifferences - 1 - %d] @ 0
                    new ScheduleStep(
                        $comparisonFactor,
                        0
                    ),
                    //teamPerformanceToPerformanceDifferenceFactors[totalTeamDifferences - 1 - %d] @ 1
                    new ScheduleStep(
                        $performancesToDifferencesFactor,
                        1
                    ),
                ]
            );
            $backwardScheduleList[] = $currentBackwardSchedulePiece;
        }

        //backward schedule
        $backwardSchedule = new ScheduleSequence($backwardScheduleList);

        $forwardBackwardScheduleToLoop =
            //forward Backward Schedule To Loop
            new ScheduleSequence(
                [$forwardSchedule, $backwardSchedule]
            );

        $initialMaxDelta = 0.0001;

        //loop with max delta
        return new ScheduleLoop(
            $forwardBackwardScheduleToLoop,
            $initialMaxDelta
        );
    }
}
