<?php

namespace Cevinio\Behat\Context;

use Behat\Behat\EventDispatcher\Event\BeforeScenarioTested;
use Cevinio\Behat\ServiceContainer\LaravelFactory;
use Illuminate\Contracts\Foundation\Application;

/**
 * @see LaravelAwareContext
 */
trait LaravelAware
{
    private LaravelFactory $factory;

    /**
     * @see LaravelAwareContext::setLaravelFactory()
     * @internal
     */
    public function setLaravelFactory(LaravelFactory $factory): void
    {
        $this->factory = $factory;
    }

    public function app(): Application
    {
        return $this->factory->get();
    }

    public function bootstrapLaravelEnvironment(BeforeScenarioTested $event): array
    {
        return [];
    }

    public function bootstrapLaravelApplication(Application $app, BeforeScenarioTested $event): void
    {
    }
}
