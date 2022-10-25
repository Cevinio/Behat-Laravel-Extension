<?php

namespace Cevinio\Behat\Context;

use Cevinio\Behat\ServiceContainer\LaravelFactory;
use Illuminate\Foundation\Application;

trait LaravelAware
{
    /** @var LaravelFactory */
    private $factory;

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

    public function initLaravelApplication(Application $application): void
    {
    }
}
