<?php

declare(strict_types=1);

namespace Justasb\Phpunit\InjectionHooks\Test\Functional;

use PHPUnit\Framework\TestCase;
use Justasb\Phpunit\InjectionHooks\InjectableHooks;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Justasb\Phpunit\InjectionHooks\Test\Functional\App\Service\Calculator;

class InjectionTest extends TestCase
{
    use InjectableHooks;

    private Calculator $calculator;
    private \IteratorAggregate $projectFiles;
    private int $secret;

    /**
     * @inject $pwdFiles app.project_files
     * @inject $secret %app.secret%
     */
    protected function setUpWith(Calculator $calculator, \IteratorAggregate $pwdFiles, int $secret): void
    {
        $this->calculator = $calculator;
        $this->projectFiles = $pwdFiles;
        $this->secret = $secret;
    }

    public function test(): void
    {
        $this->assertEquals(4, $this->calculator->multiply(2, 2));
        $this->assertEquals(9999, $this->secret);
        $this->assertEqualsCanonicalizing([
            'Kernel.php',
            'Command/TestCommand.php',
            'Service/Calculator.php',
            'Service/ProjectFiles.php',
        ], iterator_to_array($this->projectFiles));
    }
}
