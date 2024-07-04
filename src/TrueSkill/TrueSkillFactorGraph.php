<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill;

use DNW\Skills\FactorGraphs\FactorGraph;
use DNW\Skills\FactorGraphs\FactorList;
use DNW\Skills\FactorGraphs\ScheduleSequence;
use DNW\Skills\FactorGraphs\VariableFactory;
use DNW\Skills\GameInfo;
use DNW\Skills\Numerics\GaussianDistribution;
use DNW\Skills\Rating;
use DNW\Skills\Team;
use DNW\Skills\Player;
use DNW\Skills\RatingContainer;
use DNW\Skills\FactorGraphs\FactorGraphLayer;
use DNW\Skills\FactorGraphs\KeyedVariable;
use DNW\Skills\TrueSkill\Layers\IteratedTeamDifferencesInnerLayer;
use DNW\Skills\TrueSkill\Layers\PlayerPerformancesToTeamPerformancesLayer;
use DNW\Skills\TrueSkill\Layers\PlayerPriorValuesToSkillsLayer;
use DNW\Skills\TrueSkill\Layers\PlayerSkillsToPerformancesLayer;
use DNW\Skills\TrueSkill\Layers\TeamDifferencesComparisonLayer;
use DNW\Skills\TrueSkill\Layers\TeamPerformancesToTeamPerformanceDifferencesLayer;

class TrueSkillFactorGraph extends FactorGraph
{
    /**
     * @var FactorGraphLayer[] $layers
     */
    private array $layers;

    private readonly PlayerPriorValuesToSkillsLayer $priorLayer;

    /**
     * @param GameInfo $gameInfo  Parameters for the game.
     * @param Team[]   $teams     A mapping of team players and their ratings.
     * @param int[]    $teamRanks The ranks of the teams where 1 is first place. For a tie, repeat the number (e.g. 1, 2, 2).
     */
    public function __construct(private readonly GameInfo $gameInfo, array $teams, array $teamRanks)
    {
        parent::__construct();
        $this->priorLayer = new PlayerPriorValuesToSkillsLayer($this, $teams);
        $newFactory = new VariableFactory(
            static fn(): GaussianDistribution => GaussianDistribution::fromPrecisionMean(0, 0)
        );

        $this->setVariableFactory($newFactory);
        $this->layers = [
            $this->priorLayer,
            new PlayerSkillsToPerformancesLayer($this),
            new PlayerPerformancesToTeamPerformancesLayer($this),
            new IteratedTeamDifferencesInnerLayer(
                $this,
                new TeamPerformancesToTeamPerformanceDifferencesLayer($this),
                new TeamDifferencesComparisonLayer($this, $teamRanks)
            ),
        ];
    }

    public function getGameInfo(): GameInfo
    {
        return $this->gameInfo;
    }

    public function buildGraph(): void
    {
        $lastOutput = NULL;

        $layers = $this->layers;
        foreach ($layers as $currentLayer) {
            if ($lastOutput != NULL) {
                $currentLayer->setInputVariablesGroups($lastOutput);
            }

            $currentLayer->buildLayer();

            $lastOutput = $currentLayer->getOutputVariablesGroups();
        }
    }

    public function runSchedule(): void
    {
        $fullSchedule = $this->createFullSchedule();
        $fullSchedule->visit();
    }

    public function getProbabilityOfRanking(): float
    {
        $factorList = new FactorList();

        $layers = $this->layers;
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

    private function createFullSchedule(): ScheduleSequence
    {
        $fullSchedule = [];

        $layers = $this->layers;
        foreach ($layers as $currentLayer) {
            $currentPriorSchedule = $currentLayer->createPriorSchedule();
            if ($currentPriorSchedule != NULL) {
                $fullSchedule[] = $currentPriorSchedule;
            }
        }

        $allLayersReverse = array_reverse($this->layers);

        foreach ($allLayersReverse as $currentLayer) {
            $curPosteriorSchedule = $currentLayer->createPosteriorSchedule();
            if ($curPosteriorSchedule != NULL) {
                $fullSchedule[] = $curPosteriorSchedule;
            }
        }

        //Full schedule
        return new ScheduleSequence($fullSchedule);
    }

    public function getUpdatedRatings(): RatingContainer
    {
        $result = new RatingContainer();

        $priorLayerOutputVariablesGroups = $this->priorLayer->getOutputVariablesGroups();
        foreach ($priorLayerOutputVariablesGroups as $currentTeam) {
            foreach ($currentTeam as $currentPlayer) {
                $localCurrentPlayer = ($currentPlayer instanceof KeyedVariable) ? $currentPlayer->getKey() : new Player("");
                $newRating = new Rating(
                    $currentPlayer->getValue()->getMean(),
                    $currentPlayer->getValue()->getStandardDeviation()
                );

                $result->setRating($localCurrentPlayer, $newRating);
            }
        }

        return $result;
    }
}
