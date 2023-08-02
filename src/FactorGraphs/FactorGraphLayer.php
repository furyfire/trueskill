<?php

namespace DNW\Skills\FactorGraphs;

abstract class FactorGraphLayer
{
    /**
     * @var Factor[] $localFactors
     */
    private array $localFactors = [];

    private array $outputVariablesGroups = [];

    private $inputVariablesGroups = [];

    protected function __construct(private readonly FactorGraph $parentFactorGraph)
    {
    }

    protected function getInputVariablesGroups()
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

    public function setInputVariablesGroups(array $value): void
    {
        $this->inputVariablesGroups = $value;
    }

    protected function scheduleSequence(array $itemsToSequence, string $name): ScheduleSequence
    {
        return new ScheduleSequence($name, $itemsToSequence);
    }

    protected function addLayerFactor(Factor $factor): void
    {
        $this->localFactors[] = $factor;
    }

    abstract public function buildLayer();

    public function createPriorSchedule()
    {
        return null;
    }

    public function createPosteriorSchedule()
    {
        return null;
    }
}
