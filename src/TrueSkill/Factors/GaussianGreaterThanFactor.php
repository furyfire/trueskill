<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill\Factors;

use DNW\Skills\FactorGraphs\Message;
use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\Numerics\GaussianDistribution;
use DNW\Skills\TrueSkill\TruncatedGaussianCorrectionFunctions;

/**
 * Factor representing a team difference that has exceeded the draw margin.
 *
 * See the accompanying math paper for more details.
 */
class GaussianGreaterThanFactor extends GaussianFactor
{
    public function __construct(private readonly float $epsilon, Variable $variable)
    {
        parent::__construct();
        $this->createVariableToMessageBinding($variable);
    }

    #[\Override]
    public function getLogNormalization(): float
    {
        $vars = $this->getVariables();
        $marginal = $vars[0]->getValue();


        $messages = $this->getMessages();
        $message = $messages[0]->getValue();
        $messageFromVariable = GaussianDistribution::divide($marginal, $message);

        return -GaussianDistribution::logProductNormalization($messageFromVariable, $message)
        +
        log(
            GaussianDistribution::cumulativeTo(
                ($messageFromVariable->getMean() - $this->epsilon) /
                $messageFromVariable->getStandardDeviation()
            )
        );
    }

    #[\Override]
    protected function updateMessageVariable(Message $message, Variable $variable): float
    {
        $oldMarginal = clone $variable->getValue();
        $oldMessage = clone $message->getValue();
        $messageFromVar = GaussianDistribution::divide($oldMarginal, $oldMessage);

        $c = $messageFromVar->getPrecision();
        $d = $messageFromVar->getPrecisionMean();

        $sqrtC = sqrt($c);

        $dOnSqrtC = $d / $sqrtC;

        $epsilsonTimesSqrtC = $this->epsilon * $sqrtC;
        $d = $messageFromVar->getPrecisionMean();

        $denom = 1.0 - TruncatedGaussianCorrectionFunctions::wExceedsMargin($dOnSqrtC, $epsilsonTimesSqrtC);

        $newPrecision = $c / $denom;
        $newPrecisionMean = ($d +
                $sqrtC *
                TruncatedGaussianCorrectionFunctions::vExceedsMargin($dOnSqrtC, $epsilsonTimesSqrtC)) / $denom;

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
