<?php

declare(strict_types=1);

namespace Justasb\Phpunit\InjectionHooks\Hook;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @property ContainerInterface $container
 * @property string $class
 * @property bool $booted
 *
 * @method void bootKernel()
 * @method ContainerInterface getContainer()
 */
class KernelTestCaseStatics extends KernelTestCase
{
    public function __get(string $property)
    {
        return KernelTestCase::$$property;
    }

    public function __call(string $method, array $arguments)
    {
        return call_user_func_array(KernelTestCase::class . "::$method", $arguments);
    }
}
