<?php

namespace DNW\Skills\TrueSkill\Factors;

use DNW\Skills\FactorGraphs\Message;
use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\Numerics\GaussianDistribution;
use DNW\Skills\TrueSkill\TruncatedGaussianCorrectionFunctions;

/**
 * Factor representing a team difference that has not exceeded the draw margin.
 *
 * See the accompanying math paper for more details.
 */
class GaussianWithinFactor extends GaussianFactor
{
    private float $epsilon;

    public function __construct(float $epsilon, Variable $variable)
    {
        parent::__construct(sprintf('%s <= %.2f', (string)$variable, $epsilon));
        $this->epsilon = $epsilon;
        $this->createVariableToMessageBinding($variable);
    }

    public function getLogNormalization(): float
    {
        /**
 * @var Variable[] $variables
*/
        $variables = $this->getVariables();
        $marginal = $variables[0]->getValue();

        /**
 * @var Message[] $messages
*/
        $messages = $this->getMessages();
        $message = $messages[0]->getValue();
        $messageFromVariable = GaussianDistribution::divide($marginal, $message);
        $mean = $messageFromVariable->getMean();
        $std = $messageFromVariable->getStandardDeviation();
        $z = GaussianDistribution::cumulativeTo(($this->epsilon - $mean) / $std)
            -
            GaussianDistribution::cumulativeTo((-$this->epsilon - $mean) / $std);

        return -GaussianDistribution::logProductNormalization($messageFromVariable, $message) + log($z);
    }

    protected function updateMessageVariable(Message $message, Variable $variable): float
    {
        $oldMarginal = clone $variable->getValue();
        $oldMessage = clone $message->getValue();
        $messageFromVariable = GaussianDistribution::divide($oldMarginal, $oldMessage);

        $c = $messageFromVariable->getPrecision();
        $d = $messageFromVariable->getPrecisionMean();

        $sqrtC = sqrt($c);
        $dOnSqrtC = $d / $sqrtC;

        $epsilonTimesSqrtC = $this->epsilon * $sqrtC;
        $d = $messageFromVariable->getPrecisionMean();

        $denominator = 1.0 - TruncatedGaussianCorrectionFunctions::wWithinMargin($dOnSqrtC, $epsilonTimesSqrtC);
        $newPrecision = $c / $denominator;
        $newPrecisionMean = ($d +
                                $sqrtC *
                                TruncatedGaussianCorrectionFunctions::vWithinMargin($dOnSqrtC, $epsilonTimesSqrtC)
                            ) / $denominator;

        $newMarginal = GaussianDistribution::fromPrecisionMean($newPrecisionMean, $newPrecision);
        $newMessage = GaussianDistribution::divide(
            GaussianDistribution::multiply($oldMessage, $newMarginal),
            $oldMarginal
        );

        // Update the message and marginal
        $message->setValue($newMessage);
        $variable->setValue($newMarginal);

        // Return the difference in the new marginal
        return GaussianDistribution::subtract($newMarginal, $oldMarginal);
    }
}
