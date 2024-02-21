<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

use DNW\Skills\FactorGraphs\ScheduleSequence;
use DNW\Skills\TrueSkill\TrueSkillFactorGraph;

abstract class FactorGraphLayer
{
    /**
     * @var Factor[] $localFactors
     */
    private array $localFactors = [];

    /**
     * @var array<int,array<int,object>>
     */
    private array $outputVariablesGroups = [];
    /**
     * @var array<int,array<int,object>>
     */
    private array $inputVariablesGroups = [];

    protected function __construct(private readonly TrueSkillFactorGraph $parentFactorGraph)
    {
    }

    /**
     * @return array<int,array<int,object>>
     */
    protected function getInputVariablesGroups(): array
    {
        return $this->inputVariablesGroups;
    }

    // HACK

    public function getParentFactorGraph(): TrueSkillFactorGraph
    {
        return $this->parentFactorGraph;
    }

    /**
     * This reference is still needed
     *
     * @return array<int,array<int,object>>
     */
    public function &getOutputVariablesGroups(): array
    {
        return $this->outputVariablesGroups;
    }

    /**
     * @return Factor[]
     */
    public function getLocalFactors(): array
    {
        return $this->localFactors;
    }

    /**
     * @param array<int,array<int,object>> $value
     */
    public function setInputVariablesGroups(array $value): void
    {
        $this->inputVariablesGroups = $value;
    }

    /**
     * @param Schedule[] $itemsToSequence
     */
    protected function scheduleSequence(array $itemsToSequence, string $name): ScheduleSequence
    {
        return new ScheduleSequence($name, $itemsToSequence);
    }

    protected function addLayerFactor(Factor $factor): void
    {
        $this->localFactors[] = $factor;
    }

    abstract public function buildLayer(): void;

    public function createPriorSchedule(): ?ScheduleSequence
    {
        return NULL;
    }

    public function createPosteriorSchedule(): ?ScheduleSequence
    {
        return NULL;
    }
}
