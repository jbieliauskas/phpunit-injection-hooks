<?php

declare(strict_types=1);

namespace Justasb\Phpunit\InjectionHooks\Test\Unit;

use PHPUnit\Framework\TestCase;
use Justasb\Phpunit\InjectionHooks\Hook\Docblock;

class DocblockTest extends TestCase
{
    /**
     * @inject $var app.my_service
     */
    public function testService(): void
    {
        $docblock = new Docblock($this->getDocblock(__FUNCTION__));
        $service = $docblock->getService('var');
        $this->assertEquals('app.my_service', $service);
    }

    /**
     * @inject $var %app.my_param%
     */
    public function testParameter(): void
    {
        $docblock = new Docblock($this->getDocblock(__FUNCTION__));
        $parameter = $docblock->getParameter('var');
        $this->assertEquals('app.my_param', $parameter);
    }

    public function testMissing(): void
    {
        $docblock = new Docblock($this->getDocblock(__FUNCTION__));

        $service = $docblock->getService('someService');
        $parameter = $docblock->getParameter('someParameter');

        $this->assertNull($service);
        $this->assertNull($parameter);
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     */
    private function getDocblock(string $methodName): false|string
    {
        $method = new \ReflectionMethod(self::class, $methodName);

        return $method->getDocComment();
    }
}
