<?php

declare(strict_types=1);

namespace DNW\Skills\Tests\TrueSkill;

use DNW\Skills\TrueSkill\TruncatedGaussianCorrectionFunctions;
use DNW\Skills\Numerics\BasicMath;
use DNW\Skills\Numerics\GaussianDistribution;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(TruncatedGaussianCorrectionFunctions::class)]
#[UsesClass(BasicMath::class)]
#[UsesClass(GaussianDistribution::class)]
class TruncatedGaussianCorrectionFunctionsTest extends TestCase
{
    

    public function testVGreaterThan(): void {
        // Test values taken from Ralf Herbrich's F# TrueSkill implementation
        $want = 0.4181660649773850;
        $tVar = 0.7495591915280050;
        $eps = 0.0631282276750071;
        $this->assertEqualsWithDelta($want, TruncatedGaussianCorrectionFunctions::vExceedsMargin($tVar, $eps), 1e-6);
    }
    
    public function testWGreaterThan(): void {
        // Test values taken from Ralf Herbrich's F# TrueSkill implementation
        $want = 0.4619049929317120;
        $tVar = 0.7495591915280050;
        $eps = 0.0631282276750071;
        $this->assertEqualsWithDelta($want, TruncatedGaussianCorrectionFunctions::wExceedsMargin($tVar, $eps), 1e-6);
    }
    
    public function testVWithin(): void {
        // Test values taken from Ralf Herbrich's F# TrueSkill implementation
        $want = -0.7485644072749330;
        $tVar = 0.7495591915280050;
        $eps = 0.0631282276750071;
        $this->assertEqualsWithDelta($want, TruncatedGaussianCorrectionFunctions::vWithinMargin($tVar, $eps), 1e-6);
    }
    
    public function testWWithin(): void {
        // Test values taken from Ralf Herbrich's F# TrueSkill implementation
        $want = 0.9986734210033660;
        $tVar = 0.7495591915280050;
        $eps = 0.0631282276750071;
        $this->assertEqualsWithDelta($want, TruncatedGaussianCorrectionFunctions::wWithinMargin($tVar, $eps), 1e-6);   
    }
}
