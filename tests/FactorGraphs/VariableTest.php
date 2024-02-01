<?php

namespace DNW\Skills\Tests\FactorGraphs;

use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\Numerics\GaussianDistribution;
use PHPUnit\Framework\TestCase;

class VariableTest extends TestCase
{
    public function test(): void
    {
        $gd_prior = new GaussianDistribution();
        $var = new Variable('dummy', $gd_prior);
        $this->assertEquals($gd_prior, $var->getValue());
        $gd_new = new GaussianDistribution();
        $this->assertEquals($gd_new, $var->getValue());
        $var->resetToPrior();
        $this->assertEquals($gd_prior, $var->getValue());
        $this->assertEquals('Variable[dummy]', (string)$var);
    }
}
