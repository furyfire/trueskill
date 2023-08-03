<?php

namespace DNW\Skills\FactorGraphs;
use DNW\Skills\FactorGraphs\ScheduleSequence;

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
    private $inputVariablesGroups = [];

    protected function __construct(private readonly FactorGraph $parentFactorGraph)
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

    public function getParentFactorGraph(): FactorGraph
    {
        return $this->parentFactorGraph;
    }

    /**
     * This reference is still needed
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
        return null;
    }

    public function createPosteriorSchedule(): ?ScheduleSequence
    {
        return null;
    }
}
