<?php /** @noinspection PhpParamsInspection */

declare(strict_types=1);

namespace Justasb\Phpunit\InjectionHooks;

use Justasb\Phpunit\InjectionHooks\Hook\HookMethod;

trait InjectableHooks
{
    protected function setUp(): void
    {
        (new HookMethod(__FUNCTION__, $this))->call();
    }
}
