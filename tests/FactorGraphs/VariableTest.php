<?php

declare(strict_types=1);

namespace DNW\Skills\Tests\FactorGraphs;

use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\Numerics\GaussianDistribution;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Variable::class)]
#[UsesClass(GaussianDistribution::class)]
final class VariableTest extends TestCase
{
    public function testGetterSetter(): void
    {
        $gd_prior = new GaussianDistribution();
        $var      = new Variable($gd_prior);
        $this->assertEquals($gd_prior, $var->getValue());

        $gd_new = new GaussianDistribution();
        $this->assertEquals($gd_new, $var->getValue());
        $var->resetToPrior();
        $this->assertEquals($gd_prior, $var->getValue());
    }
}
