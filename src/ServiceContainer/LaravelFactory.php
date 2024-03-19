<?php

namespace Cevinio\Behat\ServiceContainer;

use Behat\Behat\EventDispatcher\Event\BeforeScenarioTested;
use Cevinio\Behat\Context\LaravelAwareContext;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Cevinio\Behat\Driver\LaravelDriver;
use Illuminate\Support\Facades\Facade;
use RuntimeException;

/** @internal */
final class LaravelFactory
{
    private ?Application $app;

    /** @var LaravelDriver[] */
    private array $drivers;

    /** @var LaravelAwareContext[] */
    private array $contexts;

    /** @var array<string, string> */
    private array $environment;

    public function __construct(
        private readonly string $bootstrapPath,
        private readonly ?string $envPath,
    ) {
        $this->app = null;
        $this->drivers = [];
        $this->contexts = [];
        $this->environment = [];
    }

    public function get(): Application
    {
        return $this->app;
    }

    public function flush(): void
    {
        if (null !== $this->app) {
            Facade::clearResolvedInstances();
            $this->app->flush();
            $this->app = null;
        }

        $this->updateEnvironment($this->environment);

        $this->contexts = [];
        $this->environment = [];
    }

    public function createApplication(BeforeScenarioTested $event): void
    {
        if (null !== $this->app) {
            throw new RuntimeException('Application is already created.');
        }

        $environment = [];

        foreach ($this->contexts as $context) {
            $environment = [ ...$environment, ...$context->bootstrapLaravelEnvironment($event) ];
        }

        $this->environment = $this->updateEnvironment($environment);

        $this->app = require $this->bootstrapPath;

        if (null !== $this->envPath) {
            $this->app->loadEnvironmentFrom($this->envPath);
        }

        $this->app->make(Kernel::class)->bootstrap();
        $this->app->make(Request::class)->capture();

        foreach ($this->drivers as $driver) {
            $driver->refreshApplication($this->app);
        }

        foreach ($this->contexts as $context) {
            $context->bootstrapLaravelApplication($this->app, $event);
        }

        $this->contexts = [];
    }

    private function updateEnvironment(array $environment): array
    {
        $original = [];

        foreach ($environment as $var => $value) {
            $original[$var] = $_SERVER[$var] ?? null;
            $_SERVER[$var] = $value;
        }

        return $original;
    }

    public function registerDriver(LaravelDriver $driver): void
    {
        if (null !== $this->app) {
            throw new RuntimeException('Cannot register Driver after Application has been created.');
        }

        $this->drivers[] = $driver;
    }

    public function registerContext(LaravelAwareContext $context): void
    {
        if (null !== $this->app) {
            throw new RuntimeException('Cannot register Context after Application has been created.');
        }

        $this->contexts[] = $context;
    }
}
