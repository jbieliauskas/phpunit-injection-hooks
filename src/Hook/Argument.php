<?php

declare(strict_types=1);

namespace Justasb\Phpunit\InjectionHooks\Hook;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Argument
{
    private \ReflectionType $type;
    private string $name;

    public function __construct(\ReflectionParameter $arg)
    {
        $this->type = $arg->getType();
        $this->name = $arg->getName();
    }

    public function resolve(Docblock $docblock, ContainerInterface $container)
    {
        return $this->type->isBuiltin()
            ? $this->resolveToParameter($docblock, $container)
            : $this->resolveToService($docblock, $container)
        ;
    }

    private function resolveToService(Docblock $docblock, ContainerInterface $container): object
    {
        $serviceName = $docblock->getService($this->name);

        if ($serviceName === null) {
            $serviceName = $this->type->getName();
        }

        return $container->get($serviceName);
    }

    private function resolveToParameter(Docblock $docblock, ContainerInterface $container)
    {
        return $container->getParameter(
            $docblock->getParameter($this->name)
        );
    }
}
