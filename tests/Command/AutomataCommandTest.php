<?php declare(strict_types=1);

namespace BpiTests\Command;

use BpiTest\Command\AutomataCommand;
use BpiTest\State\Modulus;
use PHPUnit\Framework\TestCase;

class AutomataCommandTest extends TestCase
{
    /** @var \BpiTest\Command\AutomataCommand $command */
    private $command;

    public function setUp(): void
    {
        $this->command = new AutomataCommand;
    }

    public function getTestInputStrings(): array
    {
        return [
            // From requirements document.
            ['110', 0],
            ['1010', 1],
            // Simple numbers.
            ['0', 0],
            ['1', 1],
            ['10', 2],
            // 1. Mash keypad on calculator with hand.
            // 2. mod 3
            // 3. Convert number to binary (using Google because I'm lazy).
            ['1110000111000111110111001100', 2],
            ['101000001110000010010001010111111001110100', 1],
            ['1110100111011001001101100001001000101100101', 0],
        ];
    }

    /**
     * @test
     * @dataProvider getTestInputStrings
     */
    public function testInputStringResultsInExpectedModulus(string $input, int $expectedModulus): void
    {
        $state = new Modulus;
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $input);
        rewind($stream);
        $this->command->processStreamIntoModulusResult($stream, $state);
        $this->assertEquals($expectedModulus, $state->getModulus());
    }
}
