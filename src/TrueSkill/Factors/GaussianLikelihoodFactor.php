<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill\Factors;

use DNW\Skills\FactorGraphs\KeyedVariable;
use DNW\Skills\FactorGraphs\Message;
use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\Numerics\GaussianDistribution;
use Exception;

/**
 * Connects two variables and adds uncertainty.
 *
 * See the accompanying math paper for more details.
 */
class GaussianLikelihoodFactor extends GaussianFactor
{
    private readonly float $precision;

    public function __construct(float $betaSquared, Variable $variable1, Variable $variable2)
    {
        parent::__construct(sprintf('Likelihood of %s going to %s', (string)$variable2, (string)$variable1));
        $this->precision = 1.0 / $betaSquared;
        $this->createVariableToMessageBinding($variable1);
        $this->createVariableToMessageBinding($variable2);
    }

    public function getLogNormalization(): float
    {
        /**
 * @var KeyedVariable[]|mixed $vars
*/
        $vars = $this->getVariables();
        /**
 * @var Message[] $messages
*/
        $messages = $this->getMessages();

        return GaussianDistribution::logRatioNormalization(
            $vars[0]->getValue(),
            $messages[0]->getValue()
        );
    }

    private function updateHelper(Message $message1, Message $message2, Variable $variable1, Variable $variable2): float
    {
        $message1Value = clone $message1->getValue();
        $message2Value = clone $message2->getValue();

        $marginal1 = clone $variable1->getValue();
        $marginal2 = clone $variable2->getValue();

        $a = $this->precision / ($this->precision + $marginal2->getPrecision() - $message2Value->getPrecision());

        $newMessage = GaussianDistribution::fromPrecisionMean(
            $a * ($marginal2->getPrecisionMean() - $message2Value->getPrecisionMean()),
            $a * ($marginal2->getPrecision() - $message2Value->getPrecision())
        );

        $oldMarginalWithoutMessage = GaussianDistribution::divide($marginal1, $message1Value);

        $newMarginal = GaussianDistribution::multiply($oldMarginalWithoutMessage, $newMessage);

        // Update the message and marginal

        $message1->setValue($newMessage);
        $variable1->setValue($newMarginal);

        // Return the difference in the new marginal
        return GaussianDistribution::subtract($newMarginal, $marginal1);
    }

    public function updateMessageIndex(int $messageIndex): float
    {
        $messages = $this->getMessages();
        $vars = $this->getVariables();

        return match ($messageIndex) {
            0 => $this->updateHelper(
                $messages[0],
                $messages[1],
                $vars[0], $vars[1]
            ),
            1 => $this->updateHelper(
                $messages[1],
                $messages[0],
                $vars[1], $vars[0]
            ),
            default => throw new Exception(),
        };
    }
}
