<?php

namespace Phpro\GrumPHPCombinedCoverageExtension;

use GrumPHP\Extension\ExtensionInterface;

class GrumPHPCombinedCoverageExtension implements ExtensionInterface
{
    public function imports(): iterable
    {
        $configDir = dirname(__DIR__) . '/config';

        yield $configDir . '/extension.yaml';
    }
}
