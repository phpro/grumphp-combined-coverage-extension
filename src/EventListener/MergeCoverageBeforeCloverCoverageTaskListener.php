<?php

declare(strict_types=1);

namespace Phpro\GrumPHPCombinedCoverageExtension\EventListener;

use GrumPHP\Event\TaskEvent;
use GrumPHP\Exception\RuntimeException;
use GrumPHP\Task\CloverCoverage;
use Symfony\Component\Process\Process;

final class MergeCoverageBeforeCloverCoverageTaskListener
{
    private function __construct(
        private readonly string $phpCoverageExecutable,
        private readonly string $phpCoverageDir,
    ) {
    }

    public static function create(
        ?string $phpCoverageExecutable,
        string $phpCoverageDir,
    ): self {
        return new self(
            $phpCoverageExecutable ?: dirname(__DIR__, 2).'/tools/phpcov',
            $phpCoverageDir,
        );
    }

    public function __invoke(TaskEvent $event): void
    {
        $task = $event->getTask();
        if (!$task instanceof CloverCoverage) {
            return;
        }

        $options = $task->getConfig()->getOptions();
        $target = $options['clover_file'];

        $process = new Process([
            $this->phpCoverageExecutable,
            'merge',
            '--clover',
            $target,
            $this->phpCoverageDir,
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException(
                'Unable to merge coverage files with phpcov: '
                .PHP_EOL
                .$process->getCommandLine()
                .PHP_EOL
                .$process->getOutput()
                .PHP_EOL
                .$process->getErrorOutput()
            );
        }
    }
}
