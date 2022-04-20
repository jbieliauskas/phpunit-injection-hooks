<?php

declare(strict_types=1);

namespace Justasb\Phpunit\InjectionHooks\Test\Functional\App;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Justasb\Phpunit\InjectionHooks\Test\Functional\App\Service\ProjectFiles;
use Justasb\Phpunit\InjectionHooks\Test\Functional\App\Service\Calculator;
use Justasb\Phpunit\InjectionHooks\Test\Functional\App\Command\TestCommand;

class Kernel extends SymfonyKernel
{
    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles(): iterable
    {
        return [new FrameworkBundle()];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(
            function (ContainerBuilder $container) {
                $container->loadFromExtension('framework', ['test' => true]);

                $container->setParameter('app.secret', 9999);
                $container->register(Calculator::class, Calculator::class);
                $container
                    ->register('app.project_files', ProjectFiles::class)
                    ->setArgument('$projectDir', '%kernel.project_dir%')
                ;

                $container
                    ->register(TestCommand::class, TestCommand::class)
                    ->setArgument('$files', new Reference('app.project_files'))
                    ->setArgument('$secret', '%app.secret%')
                    ->setAutoconfigured(true)
                    ->setAutowired(true)
                ;
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function getProjectDir(): string
    {
        return __DIR__;
    }

    /**
     * @inheritDoc
     */
    public function getCacheDir(): string
    {
        return __DIR__ . '/../var/cache';
    }

    /**
     * @inheritdoc
     */
    public function getLogDir(): string
    {
        return __DIR__ . '/../var/log';
    }
}
