<?php

namespace Cevinio\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\EventDispatcher\Event\BeforeScenarioTested;
use Cevinio\Behat\ServiceContainer\LaravelFactory;
use Illuminate\Contracts\Foundation\Application;

/**
 * @see LaravelAware
 */
interface LaravelAwareContext extends Context
{
    /** @internal */
    public function setLaravelFactory(LaravelFactory $factory): void;

    /** @return array<string, string> */
    public function bootstrapLaravelEnvironment(BeforeScenarioTested $event): array;

    public function bootstrapLaravelApplication(Application $app, BeforeScenarioTested $event): void;
}
