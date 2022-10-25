<?php

namespace Cevinio\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Behat\Testwork\EventDispatcher\Event\SuiteTested;
use Cevinio\Behat\ServiceContainer\LaravelFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class LaravelAwareInitializer implements EventSubscriberInterface, ContextInitializer
{
    /** @var LaravelFactory */
    private $factory;

    public function __construct(LaravelFactory $factory)
    {
        $this->factory = $factory;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SuiteTested::BEFORE => [ 'createApplication', 25 ],
            SuiteTested::AFTER_SETUP => [ 'flushApplication', -25 ],
            ScenarioTested::BEFORE => [ 'createApplication', 25 ],
            ScenarioTested::AFTER => [ 'flushApplication', -25 ],
            SuiteTested::BEFORE_TEARDOWN => [ 'createApplication', 25 ],
            SuiteTested::AFTER => [ 'flushApplication', -25 ],
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

    public function createApplication(): void
    {
        $this->factory->createApplication();
    }

    public function flushApplication(): void
    {
        $this->factory->flush();
    }
}
