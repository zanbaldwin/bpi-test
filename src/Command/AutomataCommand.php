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
        $stateMachine = new Workflow($definition, new MethodMarkingStore(true, 'state'));

        $modulus = new Modulus;

        foreach ($this->getCharacterFromInputStream() as $character) {
            $transitionName = $this->determineTransitionName($modulus, $character);
            if (!$stateMachine->can($modulus, $transitionName)) {
                throw new \LogicException('Trying to apply invalid transition in finite state machine.');
            }
            $stateMachine->apply($modulus, $transitionName);
        }

        return 0;
    }

    private function getCharacterFromInputStream(): iterable
    {
    }

    private function determineTransitionName(StatefulObjectInterface $state, string $character): string
    {
        // Transition name (as per the state machine definition) is <currentState>_<inputCharacter>.
    }
}
