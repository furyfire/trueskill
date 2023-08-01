<?php

namespace DNW\Skills\FactorGraphs;

abstract class FactorGraphLayer
{
    private array $_localFactors = [];

    private array $_outputVariablesGroups = [];

    private $_inputVariablesGroups = [];

    protected function __construct(private readonly FactorGraph $_parentFactorGraph)
    {
    }

    protected function getInputVariablesGroups()
    {
        return $this->_inputVariablesGroups;
    }

    // HACK

    public function getParentFactorGraph()
    {
        return $this->_parentFactorGraph;
    }

    /**
     * This reference is still needed
     *
     * @return array
     */
    public function &getOutputVariablesGroups()
    {
        return $this->_outputVariablesGroups;
    }

    public function getLocalFactors()
    {
        return $this->_localFactors;
    }

    public function setInputVariablesGroups($value)
    {
        $this->_inputVariablesGroups = $value;
    }

    protected function scheduleSequence(array $itemsToSequence, $name): ScheduleSequence
    {
        return new ScheduleSequence($name, $itemsToSequence);
    }

    protected function addLayerFactor(Factor $factor)
    {
        $this->_localFactors[] = $factor;
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
