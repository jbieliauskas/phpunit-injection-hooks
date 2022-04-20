<?php

declare(strict_types=1);

namespace Justasb\Phpunit\InjectionHooks\Test\Functional\App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Justasb\Phpunit\InjectionHooks\Test\Functional\App\Service\Calculator;

class TestCommand extends Command
{
    public function __construct(
        private Calculator $calculator,
        private \IteratorAggregate $files,
        private int $secret
    ) {
        parent::__construct('test:services');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('2 * 2 = ' . $this->calculator->multiply(2, 2));
        $output->writeln("secret: $this->secret");

        $output->writeln('Files:');
        foreach ($this->files as $file) {
            $output->writeln($file);
        }

        return self::SUCCESS;
    }
}
