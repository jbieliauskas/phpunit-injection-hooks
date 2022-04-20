<?php

declare(strict_types=1);

namespace Justasb\Phpunit\InjectionHooks\Hook;

use PHPUnit\Framework\TestCase;

class HookMethod
{
    private KernelTestCaseStatics $kernelTestCaseStatics;

    public function __construct(
        private string $name,
        private TestCase $test,
    ) {
        $this->kernelTestCaseStatics = new KernelTestCaseStatics();
    }

    public function call(): void
    {
        $withMethod = $this->getWithMethod();

        if (!$withMethod) {
            return;
        }

        $docblock = new Docblock($withMethod->getDocComment());
        $container = $this->kernelTestCaseStatics->getContainer();

        $resolvedArgs = array_map(
            function (\ReflectionParameter $arg) use ($docblock, $container) {
                return (new Argument($arg))->resolve($docblock, $container);
            },
            $withMethod->getParameters()
        );

        $withMethod->setAccessible(true);
        $withMethod->invoke($this->test, ...$resolvedArgs);
    }

    private function getWithMethod(): ?\ReflectionMethod
    {
        $methodName = $this->name . 'With';

        return method_exists($this->test, $methodName)
            ? new \ReflectionMethod($this->test, $methodName)
            : null
        ;
    }
}
