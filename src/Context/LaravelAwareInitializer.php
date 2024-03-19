<?php

namespace Cevinio\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\Behat\EventDispatcher\Event\BeforeScenarioTested;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Cevinio\Behat\ServiceContainer\LaravelFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class LaravelAwareInitializer implements EventSubscriberInterface, ContextInitializer
{
    public function __construct(
        private readonly LaravelFactory $factory,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ScenarioTested::BEFORE => [ 'createApplication', 25 ],
            ScenarioTested::AFTER => [ 'flushApplication', -25 ],
        ];
    }

    public function initializeContext(Context $context): void
    {
        if (false === ($context instanceof LaravelAwareContext)) {
            return;
        }

        $context->setLaravelFactory($this->factory);
        $this->factory->registerContext($context);
    }

    public function createApplication(BeforeScenarioTested $event): void
    {
        $this->factory->createApplication($event);
    }

    public function flushApplication(): void
    {
        $this->factory->flush();
    }
}
