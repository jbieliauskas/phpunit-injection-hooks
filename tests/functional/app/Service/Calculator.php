<?php

declare(strict_types=1);

namespace Justasb\Phpunit\InjectionHooks\Test\Functional\App\Service;

class Calculator
{
    public function multiply(int $a, int $b): int
    {
        return $a * $b;
    }
}
