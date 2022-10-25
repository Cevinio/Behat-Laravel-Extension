<?php

namespace Cevinio\Behat\Context;

use Behat\Behat\Context\Context;
use Cevinio\Behat\ServiceContainer\LaravelFactory;
use Illuminate\Foundation\Application;

interface LaravelAwareContext extends Context
{
    /** @internal */
    public function setLaravelFactory(LaravelFactory $factory): void;

    public function initLaravelApplication(Application $application): void;
}
