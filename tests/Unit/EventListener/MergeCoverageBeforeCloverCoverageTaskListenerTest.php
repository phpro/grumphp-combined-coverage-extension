<?php

declare(strict_types=1);

namespace Phpro\GrumPHPCombinedCoverageExtensionTests\Unit\EventListener;

use GrumPHP\Collection\FilesCollection;
use GrumPHP\Event\TaskEvent;
use GrumPHP\Exception\RuntimeException;
use GrumPHP\Task\CloverCoverage;
use GrumPHP\Task\Config\Metadata;
use GrumPHP\Task\Config\TaskConfig;
use GrumPHP\Task\Context\RunContext;
use GrumPHP\Task\TaskInterface;
use Phpro\GrumPHPCombinedCoverageExtension\EventListener\MergeCoverageBeforeCloverCoverageTaskListener;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(MergeCoverageBeforeCloverCoverageTaskListener::class)]
final class MergeCoverageBeforeCloverCoverageTaskListenerTest extends TestCase
{
    private static function createListener(): MergeCoverageBeforeCloverCoverageTaskListener
    {
        return MergeCoverageBeforeCloverCoverageTaskListener::create(
            dirname(__DIR__, 2).'/fixtures/mock-phpcov.php',
            'php-coverage-dir',
        );
    }

    private static function createEvent(TaskInterface $task): TaskEvent
    {
        return new TaskEvent($task, new RunContext(new FilesCollection()));
    }

    #[Test]
    public function it_does_not_run_on_non_clover_task(): void
    {
        $task = $this->createMock(TaskInterface::class);
        $listener = self::createListener();
        $event = self::createEvent($task);

        $task->expects(self::never())->method('getConfig');

        $listener($event);
    }

    #[Test]
    public function it_runs_on_clover_task(): void
    {
        $task = $this->createMock(CloverCoverage::class);
        $listener = self::createListener();
        $event = self::createEvent($task);

        $task->expects(self::once())->method('getConfig')->willReturn(new TaskConfig(
            'clover_coverage',
            ['clover_file' => 'clover.xml'],
            new Metadata([]),
        ));

        $listener($event);
    }

    #[Test]
    public function it_fails_when_merging_coverage_fails(): void
    {
        $task = $this->createMock(CloverCoverage::class);
        $listener = self::createListener();
        $event = self::createEvent($task);

        $task->expects(self::once())->method('getConfig')->willReturn(new TaskConfig(
            'clover_coverage',
            ['clover_file' => 'invalid-clover.xml'],
            new Metadata([]),
        ));

        $this->expectException(RuntimeException::class);
        $listener($event);
    }
}
