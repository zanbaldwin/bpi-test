<?php declare(strict_types=1);

namespace BpiTest\State;

class Modulus implements StatefulObjectInterface
{
    private $state = 'S0';

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        if (preg_match('/^S[012]$/', $state)) {
            $this->state = $state;
        }
    }

    public function getModulus(): int
    {
        return (int) substr($this->state, 1, 1);
    }
}
