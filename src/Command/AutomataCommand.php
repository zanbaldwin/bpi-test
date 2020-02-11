<?php declare(strict_types=1);

namespace BpiTest\Command;

use BpiTest\State\Modulus;
use BpiTest\State\StatefulObjectInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

class AutomataCommand extends Command
{
    /** @var string $defaultName */
    protected static $defaultName = 'automata';

    protected function configure(): void
    {
        $this->setDescription('Calculate the modulus-three of an input using a finite state machine.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $modulus = new Modulus;
        $this->processStreamIntoModulusResult(\STDIN, $modulus);
        $output->writeln(sprintf('The input stream modulus-three is %d.', $modulus->getModulus()));

        return 0;
    }

    public function processStreamIntoModulusResult($stream, StatefulObjectInterface $state): void
    {
        $stateMachine = $this->createFiniteStateMachine();
        foreach ($this->getCharacterFromStream($stream) as $character) {
            $transitionName = $this->determineTransitionName($state, $character);
            if (!$stateMachine->can($state, $transitionName)) {
                throw new \LogicException('Trying to apply invalid transition in finite state machine.');
            }
            $stateMachine->apply($state, $transitionName);
        }
    }

    private function createFiniteStateMachine(): Workflow
    {
        $definitionBuilder = new DefinitionBuilder;
        $definition = $definitionBuilder
            ->addPlaces(['S0', 'S1', 'S2'])
            ->addTransition(new Transition('S0_0', 'S0', 'S0'))
            ->addTransition(new Transition('S0_1', 'S0', 'S1'))
            ->addTransition(new Transition('S1_0', 'S1', 'S2'))
            ->addTransition(new Transition('S1_1', 'S1', 'S0'))
            ->addTransition(new Transition('S2_0', 'S2', 'S1'))
            ->addTransition(new Transition('S2_1', 'S2', 'S2'))
            ->build();
        return new Workflow($definition, new MethodMarkingStore(true, 'state'));
    }

    private function getCharacterFromStream($handle): iterable
    {
        if (!is_resource($handle)) {
            throw new \TypeError(sprintf('Expected resource, got %s.', gettype($handle)));
        }
        while (!feof($handle) && false !== $character = fread($handle, 1)) {
            if (!in_array($character, ['0', '1'])) {
                continue;
            }
            yield $character;
        }
    }

    private function determineTransitionName(StatefulObjectInterface $state, string $character): string
    {
        return sprintf('%s_%s', $state->getState(), $character);
    }
}
