<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill\Factors;

use DNW\Skills\FactorGraphs\Message;
use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\Guard;
use DNW\Skills\Team;
use DNW\Skills\Numerics\BasicMath;
use DNW\Skills\Numerics\GaussianDistribution;

/**
 * Factor that sums together multiple Gaussians.
 *
 * See the accompanying math paper for more details.
 */
class GaussianWeightedSumFactor extends GaussianFactor
{
    /**
     * @var array<int[]> $variableIndexOrdersForWeights
     */
    private array $variableIndexOrdersForWeights = [];

    /**
     * This following is used for convenience, for example, the first entry is [0, 1, 2]
     * corresponding to v[0] = a1*v[1] + a2*v[2]
     *
     * @var array<float[]> $weights
     */
    private array $weights = [];

    /**
     * @var array<float[]> $weightsSquared
     */
    private array $weightsSquared = [];

    /**
     * @param Variable[] $variablesToSum
     * @param array<float> $variableWeights
     */
    public function __construct(Variable $sumVariable, array $variablesToSum, array $variableWeights)
    {
        parent::__construct('$sumVariable, $variablesToSum, $variableWeights');

        // The first weights are a straightforward copy
        // v_0 = a_1*v_1 + a_2*v_2 + ... + a_n * v_n
        $variableWeightsLength = count($variableWeights);
        $this->weights[0] = array_fill(0, count($variableWeights), 0);

        for ($i = 0; $i < $variableWeightsLength; ++$i) {
            $weight = &$variableWeights[$i];
            $this->weights[0][$i] = $weight;
            $this->weightsSquared[0][$i] = BasicMath::square($weight);
        }

        $variablesToSumLength = count($variablesToSum);

        // 0..n-1
        $this->variableIndexOrdersForWeights[0] = [];
        for ($i = 0; $i < ($variablesToSumLength + 1); ++$i) {
            $this->variableIndexOrdersForWeights[0][] = $i;
        }

        $variableWeightsLength = count($variableWeights);

        // The rest move the variables around and divide out the constant.
        // For example:
        // v_1 = (-a_2 / a_1) * v_2 + (-a3/a1) * v_3 + ... + (1.0 / a_1) * v_0
        // By convention, we'll put the v_0 term at the end

        $weightsLength = $variableWeightsLength + 1;
        for ($weightsIndex = 1; $weightsIndex < $weightsLength; ++$weightsIndex) {
            $currentWeights = \array_fill(0, $variableWeightsLength, 0);

            $variableIndices = \array_fill(0, $variableWeightsLength + 1, 0);
            $variableIndices[0] = $weightsIndex;

            $currentWeightsSquared = \array_fill(0, $variableWeightsLength, 0);

            // keep a single variable to keep track of where we are in the array.
            // This is helpful since we skip over one of the spots
            $currentDestinationWeightIndex = 0;

            for ($currentWeightSourceIndex = 0; $currentWeightSourceIndex < $variableWeightsLength; ++$currentWeightSourceIndex) {
                if ($currentWeightSourceIndex === $weightsIndex - 1) {
                    continue;
                }

                $currentWeight = (-$variableWeights[$currentWeightSourceIndex] / $variableWeights[$weightsIndex - 1]);

                if ($variableWeights[$weightsIndex - 1] == 0) {
                    // HACK: Getting around division by zero
                    $currentWeight = 0;
                }

                $currentWeights[$currentDestinationWeightIndex] = $currentWeight;
                $currentWeightsSquared[$currentDestinationWeightIndex] = $currentWeight * $currentWeight;

                $variableIndices[$currentDestinationWeightIndex + 1] = $currentWeightSourceIndex + 1;
                ++$currentDestinationWeightIndex;
            }

            // And the final one
            $finalWeight = 1.0 / $variableWeights[$weightsIndex - 1];

            if ($variableWeights[$weightsIndex - 1] == 0) {
                // HACK: Getting around division by zero
                $finalWeight = 0;
            }

            $currentWeights[$currentDestinationWeightIndex] = $finalWeight;
            $currentWeightsSquared[$currentDestinationWeightIndex] = BasicMath::square($finalWeight);
            $variableIndices[count($variableWeights)] = 0;
            $this->variableIndexOrdersForWeights[] = $variableIndices;

            $this->weights[$weightsIndex] = $currentWeights;
            $this->weightsSquared[$weightsIndex] = $currentWeightsSquared;
        }

        $this->createVariableToMessageBinding($sumVariable);

        foreach ($variablesToSum as $currentVariable) {
            $localCurrentVariable = $currentVariable;
            $this->createVariableToMessageBinding($localCurrentVariable);
        }
    }

    public function getLogNormalization(): float
    {
        $vars = $this->getVariables();
        $messages = $this->getMessages();

        $result = 0.0;
        // We start at 1 since offset 0 has the sum
        $counter = count($vars);

        // We start at 1 since offset 0 has the sum
        for ($i = 1; $i < $counter; ++$i) {
            $result += GaussianDistribution::logRatioNormalization($vars[$i]->getValue(), $messages[$i]->getValue());
        }

        return $result;
    }

    /**
     * @param float[] $weights
     * @param float[] $weightsSquared
     * @param Message[] $messages
     * @param Variable[] $variables
     */
    private function updateHelper(array $weights, array $weightsSquared, array $messages, array $variables): float
    {
        // Potentially look at http://mathworld.wolfram.com/NormalSumDistribution.html for clues as
        // to what it's doing

        $message0 = clone $messages[0]->getValue();
        $marginal0 = clone $variables[0]->getValue();

        // The math works out so that 1/newPrecision = sum of a_i^2 /marginalsWithoutMessages[i]
        $inverseOfNewPrecisionSum = 0.0;
        $anotherInverseOfNewPrecisionSum = 0.0;
        $weightedMeanSum = 0.0;
        $anotherWeightedMeanSum = 0.0;

        $weightsSquaredLength = count($weightsSquared);

        for ($i = 0; $i < $weightsSquaredLength; ++$i) {
            // These flow directly from the paper

            $inverseOfNewPrecisionSum += $weightsSquared[$i] /
                ($variables[$i + 1]->getValue()->getPrecision() - $messages[$i + 1]->getValue()->getPrecision());

            $diff = GaussianDistribution::divide($variables[$i + 1]->getValue(), $messages[$i + 1]->getValue());
            $anotherInverseOfNewPrecisionSum += $weightsSquared[$i] / $diff->getPrecision();

            $weightedMeanSum += $weights[$i]
                *
                ($variables[$i + 1]->getValue()->getPrecisionMean() - $messages[$i + 1]->getValue()->getPrecisionMean())
                /
                ($variables[$i + 1]->getValue()->getPrecision() - $messages[$i + 1]->getValue()->getPrecision());

            $anotherWeightedMeanSum += $weights[$i] * $diff->getPrecisionMean() / $diff->getPrecision();
        }

        $newPrecision = 1.0 / $inverseOfNewPrecisionSum;

        $newPrecisionMean = $newPrecision * $weightedMeanSum;

        $newMessage = GaussianDistribution::fromPrecisionMean($newPrecisionMean, $newPrecision);
        $oldMarginalWithoutMessage = GaussianDistribution::divide($marginal0, $message0);

        $newMarginal = GaussianDistribution::multiply($oldMarginalWithoutMessage, $newMessage);

        // Update the message and marginal

        $messages[0]->setValue($newMessage);
        $variables[0]->setValue($newMarginal);

        // Return the difference in the new marginal
        $finalDiff = GaussianDistribution::subtract($newMarginal, $marginal0);

        return $finalDiff;
    }

    public function updateMessageIndex(int $messageIndex): float
    {
        $allMessages = $this->getMessages();
        $allVariables = $this->getVariables();

        Guard::argumentIsValidIndex($messageIndex, count($allMessages), 'messageIndex');

        $updatedMessages = [];
        $updatedVariables = [];

        $indicesToUse = $this->variableIndexOrdersForWeights[$messageIndex];
        // The tricky part here is that we have to put the messages and variables in the same
        // order as the weights. Thankfully, the weights and messages share the same index numbers,
        // so we just need to make sure they're consistent
        $counter = count($allMessages);

        // The tricky part here is that we have to put the messages and variables in the same
        // order as the weights. Thankfully, the weights and messages share the same index numbers,
        // so we just need to make sure they're consistent
        for ($i = 0; $i < $counter; ++$i) {
            $updatedMessages[] = $allMessages[$indicesToUse[$i]];
            $updatedVariables[] = $allVariables[$indicesToUse[$i]];
        }

        return $this->updateHelper(
            $this->weights[$messageIndex],
            $this->weightsSquared[$messageIndex],
            $updatedMessages,
            $updatedVariables
        );
    }
}
