<?php declare(strict_types=1);

namespace BpiTest\State;

interface StatefulObjectInterface
{
    public function getState(): string;
    public function setState(string $state): void;
}
