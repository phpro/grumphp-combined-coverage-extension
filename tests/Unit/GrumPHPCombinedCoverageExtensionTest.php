<?php

declare(strict_types=1);

namespace Phpro\GrumPHPCombinedCoverageExtensionTests\Unit;

use GrumPHP\Extension\ExtensionInterface;
use Phpro\GrumPHPCombinedCoverageExtension\GrumPHPCombinedCoverageExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

#[CoversClass(GrumPHPCombinedCoverageExtension::class)]
final class GrumPHPCombinedCoverageExtensionTest extends TestCase
{
    #[Test]
    public function it_configures_grumphp(): void
    {
        $extension = new GrumPHPCombinedCoverageExtension();
        self::assertInstanceOf(ExtensionInterface::class, $extension);

        $imports = [...$extension->imports()];
        self::assertGreaterThan(0, $imports);
        foreach ($imports as $import) {
            self::assertFileExists($import);
            Yaml::parseFile($import);
        }
    }
}
