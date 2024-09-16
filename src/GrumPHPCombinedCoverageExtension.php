<?php

declare(strict_types=1);

namespace Phpro\GrumPHPCombinedCoverageExtension;

use GrumPHP\Extension\ExtensionInterface;

final class GrumPHPCombinedCoverageExtension implements ExtensionInterface
{
    public function imports(): iterable
    {
        $configDir = dirname(__DIR__).'/config';

        yield $configDir.'/extension.yaml';
    }
}
