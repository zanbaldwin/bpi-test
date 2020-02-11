<?php declare(strict_types=1);

namespace BpiTest\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AutomataCommand extends Command
{
    /** @var string $defaultName */
    protected static $defaultName = 'automata';

    protected function configure(): void
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // ...
        return 0;
    }
}
