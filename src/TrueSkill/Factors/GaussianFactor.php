<?php namespace DNW\Skills\TrueSkill\Factors;

use DNW\Skills\FactorGraphs\Factor;
use DNW\Skills\FactorGraphs\Message;
use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\Numerics\GaussianDistribution;

abstract class GaussianFactor extends Factor
{
    protected function __construct($name)
    {
        parent::__construct($name);
    }

    /**
     * Sends the factor-graph message with and returns the log-normalization constant.
     * @param Message $message
     * @param Variable $variable
     * @return float|int
     */
    protected function sendMessageVariable(Message $message, Variable $variable)
    {
        $marginal = $variable->getValue();
        $messageValue = $message->getValue();
        $logZ = GaussianDistribution::logProductNormalization($marginal, $messageValue);
        $variable->setValue(GaussianDistribution::multiply($marginal, $messageValue));
        return $logZ;
    }

    public function createVariableToMessageBinding(Variable $variable)
    {
        $newDistribution = GaussianDistribution::fromPrecisionMean(0, 0);
        $binding = parent::createVariableToMessageBindingWithMessage($variable,
            new Message(
                $newDistribution,
                sprintf("message from %s to %s", $this, $variable)));
        return $binding;
    }
}