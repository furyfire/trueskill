<?php

namespace DNW\Skills\TrueSkill\Factors;

use DNW\Skills\FactorGraphs\Message;
use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\Numerics\GaussianDistribution;

/**
 * Supplies the factor graph with prior information.
 *
 * See the accompanying math paper for more details.
 */
class GaussianPriorFactor extends GaussianFactor
{
    private $_newMessage;

    public function __construct(float $mean, float $variance, Variable $variable)
    {
        parent::__construct(sprintf('Prior value going to %s', $variable));
        $this->_newMessage = new GaussianDistribution($mean, sqrt($variance));
        $newMessage = new Message(
            GaussianDistribution::fromPrecisionMean(0, 0),
            sprintf('message from %s to %s', $this, $variable)
        );

        $this->createVariableToMessageBindingWithMessage($variable, $newMessage);
    }

    protected function updateMessageVariable(Message $message, Variable $variable): float
    {
        $oldMarginal = clone $variable->getValue();
        $oldMessage = $message;
        $newMarginal = GaussianDistribution::fromPrecisionMean(
            $oldMarginal->getPrecisionMean() + $this->_newMessage->getPrecisionMean() - $oldMessage->getValue()->getPrecisionMean(),
            $oldMarginal->getPrecision() + $this->_newMessage->getPrecision() - $oldMessage->getValue()->getPrecision()
        );

        $variable->setValue($newMarginal);
        $message->setValue($this->_newMessage);

        return GaussianDistribution::subtract($oldMarginal, $newMarginal);
    }
}
