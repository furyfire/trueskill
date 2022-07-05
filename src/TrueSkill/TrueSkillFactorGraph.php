<?php

namespace DNW\Skills\TrueSkill;

use DNW\Skills\FactorGraphs\FactorGraph;
use DNW\Skills\FactorGraphs\FactorList;
use DNW\Skills\FactorGraphs\ScheduleSequence;
use DNW\Skills\FactorGraphs\VariableFactory;
use DNW\Skills\GameInfo;
use DNW\Skills\Numerics\GaussianDistribution;
use DNW\Skills\Rating;
use DNW\Skills\RatingContainer;
use DNW\Skills\TrueSkill\Layers\IteratedTeamDifferencesInnerLayer;
use DNW\Skills\TrueSkill\Layers\PlayerPerformancesToTeamPerformancesLayer;
use DNW\Skills\TrueSkill\Layers\PlayerPriorValuesToSkillsLayer;
use DNW\Skills\TrueSkill\Layers\PlayerSkillsToPerformancesLayer;
use DNW\Skills\TrueSkill\Layers\TeamDifferencesComparisonLayer;
use DNW\Skills\TrueSkill\Layers\TeamPerformancesToTeamPerformanceDifferencesLayer;

class TrueSkillFactorGraph extends FactorGraph
{
    private $_layers;

    private $_priorLayer;

    public function __construct(private readonly GameInfo $_gameInfo, array $teams, array $teamRanks)
    {
        $this->_priorLayer = new PlayerPriorValuesToSkillsLayer($this, $teams);
        $newFactory = new VariableFactory(
            fn () => GaussianDistribution::fromPrecisionMean(0, 0));

        $this->setVariableFactory($newFactory);
        $this->_layers = [
            $this->_priorLayer,
            new PlayerSkillsToPerformancesLayer($this),
            new PlayerPerformancesToTeamPerformancesLayer($this),
            new IteratedTeamDifferencesInnerLayer(
                $this,
                new TeamPerformancesToTeamPerformanceDifferencesLayer($this),
                new TeamDifferencesComparisonLayer($this, $teamRanks)),
        ];
    }

    public function getGameInfo()
    {
        return $this->_gameInfo;
    }

    public function buildGraph()
    {
        $lastOutput = null;

        $layers = $this->_layers;
        foreach ($layers as $currentLayer) {
            if ($lastOutput != null) {
                $currentLayer->setInputVariablesGroups($lastOutput);
            }

            $currentLayer->buildLayer();

            $lastOutput = $currentLayer->getOutputVariablesGroups();
        }
    }

    public function runSchedule()
    {
        $fullSchedule = $this->createFullSchedule();
        $fullSchedule->visit();
    }

    public function getProbabilityOfRanking()
    {
        $factorList = new FactorList();

        $layers = $this->_layers;
        foreach ($layers as $currentLayer) {
            $localFactors = $currentLayer->getLocalFactors();
            foreach ($localFactors as $currentFactor) {
                $localCurrentFactor = $currentFactor;
                $factorList->addFactor($localCurrentFactor);
            }
        }

        $logZ = $factorList->getLogNormalization();

        return exp($logZ);
    }

    private function createFullSchedule()
    {
        $fullSchedule = [];

        $layers = $this->_layers;
        foreach ($layers as $currentLayer) {
            $currentPriorSchedule = $currentLayer->createPriorSchedule();
            if ($currentPriorSchedule != null) {
                $fullSchedule[] = $currentPriorSchedule;
            }
        }

        $allLayersReverse = array_reverse($this->_layers);

        foreach ($allLayersReverse as $currentLayer) {
            $currentPosteriorSchedule = $currentLayer->createPosteriorSchedule();
            if ($currentPosteriorSchedule != null) {
                $fullSchedule[] = $currentPosteriorSchedule;
            }
        }

        return new ScheduleSequence('Full schedule', $fullSchedule);
    }

    public function getUpdatedRatings()
    {
        $result = new RatingContainer();

        $priorLayerOutputVariablesGroups = $this->_priorLayer->getOutputVariablesGroups();
        foreach ($priorLayerOutputVariablesGroups as $currentTeam) {
            foreach ($currentTeam as $currentPlayer) {
                $localCurrentPlayer = $currentPlayer->getKey();
                $newRating = new Rating($currentPlayer->getValue()->getMean(),
                    $currentPlayer->getValue()->getStandardDeviation());

                $result->setRating($localCurrentPlayer, $newRating);
            }
        }

        return $result;
    }
}
