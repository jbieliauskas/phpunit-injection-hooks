<?php

declare(strict_types=1);

namespace Justasb\Phpunit\InjectionHooks\Hook;

class Docblock
{
    private string $docblock;
    private ?array $injections = null;

    public function __construct(false|string $docblock)
    {
        $this->docblock = $docblock === false ? '' : $docblock;
    }

    public function getService(string $var): ?string
    {
        $injections = $this->parse();

        return $injections[$var] ?? null;
    }

    public function getParameter(string $var): ?string
    {
        $service = $this->getService($var);

        if ($service === null) {
            return null;
        }

        return substr($service, 1, -1);
    }

    private function parse(): array
    {
        if ($this->injections === null) {
            preg_match_all(
                '/@inject +\$([a-zA-Z0-9_]+) +(.+)$/m',
                $this->docblock,
                $matches,
                PREG_SET_ORDER
            );

            $this->injections = [];
            foreach ($matches as [$full, $arg, $service]) {
                $this->injections[$arg] = $service;
            }
        }

        return $this->injections;
    }
}
