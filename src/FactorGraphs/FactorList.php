<?php

namespace DNW\Skills\FactorGraphs;

/**
 * Helper class for computing the factor graph's normalization constant.
 */
class FactorList
{
    /**
     * @var Factor[] $list
     */
    private array $list = [];

    public function getLogNormalization(): float
    {
        $list = $this->list;
        foreach ($list as &$currentFactor) {
            $currentFactor->resetMarginals();
        }

        $sumLogZ = 0.0;

        $listCount = count($this->list);

        for ($i = 0; $i < $listCount; $i++) {
            $f = $this->list[$i];

            $numberOfMessages = $f->getNumberOfMessages();

            for ($j = 0; $j < $numberOfMessages; $j++) {
                $sumLogZ += $f->sendMessageIndex($j);
            }
        }

        $sumLogS = 0;

        foreach ($list as &$currentFactor) {
            $sumLogS += $currentFactor->getLogNormalization();
        }

        return $sumLogZ + $sumLogS;
    }

    public function count(): int
    {
        return count($this->list);
    }

    public function addFactor(Factor $factor): Factor
    {
        $this->list[] = $factor;

        return $factor;
    }
}
