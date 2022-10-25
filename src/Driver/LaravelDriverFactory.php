<?php

namespace Cevinio\Behat\Driver;

use Behat\MinkExtension\ServiceContainer\Driver\DriverFactory;
use Cevinio\Behat\ServiceContainer\BehatExtension;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class LaravelDriverFactory implements DriverFactory
{
    public const LARAVEL_DRIVER = 'laravel';

    public function getDriverName(): string
    {
        return self::LARAVEL_DRIVER;
    }

    public function supportsJavascript(): bool
    {
        return false;
    }

    public function configure(ArrayNodeDefinition $builder): void
    {
    }

    public function buildDriver(array $config): Definition
    {
        return new Definition(
            LaravelDriver::class,
            [ new Reference(BehatExtension::LARAVEL_FACTORY), '%mink.base_url%' ]
        );
    }
}
