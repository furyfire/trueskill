<?php

namespace DNW\Skills\TrueSkill\Factors;

use DNW\Skills\FactorGraphs\Factor;
use DNW\Skills\FactorGraphs\Message;
use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\Numerics\GaussianDistribution;

abstract class GaussianFactor extends Factor
{
    /**
     * Sends the factor-graph message with and returns the log-normalization constant.
     */
    protected function sendMessageVariable(Message $message, Variable $variable): float|int
    {
        $marginal = $variable->getValue();
        $messageValue = $message->getValue();
        $logZ = GaussianDistribution::logProductNormalization($marginal, $messageValue);
        $variable->setValue(GaussianDistribution::multiply($marginal, $messageValue));

        return $logZ;
    }

    public function createVariableToMessageBinding(Variable $variable): Message
    {
        $newDistribution = GaussianDistribution::fromPrecisionMean(0, 0);

        return parent::createVariableToMessageBindingWithMessage(
            $variable,
            new Message(
                $newDistribution,
                sprintf('message from %s to %s', (string)$this, (string)$variable)
            )
        );
    }
}
