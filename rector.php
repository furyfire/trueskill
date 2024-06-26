<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/benchmark',
        __DIR__ . '/examples',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets()
    ->withPreparedSets(deadCode: true, codeQuality: true, codingStyle: true, typeDeclarations : true, privatization: true, naming: false, instanceOf: true, earlyReturn: true, strictBooleans: true)
    ->withSkip([
        LocallyCalledStaticMethodToNonStaticRector::class
    ]);;
