<?php

namespace Cevinio\Behat\ServiceContainer;

use Cevinio\Behat\Context\LaravelAwareContext;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Cevinio\Behat\Driver\LaravelDriver;
use RuntimeException;

/** @internal */
final class LaravelFactory
{
    /** @var string */
    private $bootstrapPath;

    /** @var Application|null */
    private $app;

    /** @var LaravelDriver[] */
    private $drivers;

    /** @var LaravelAwareContext[] */
    private $contexts;

    public function __construct(string $bootstrapPath)
    {
        $this->bootstrapPath = $bootstrapPath;
        $this->drivers = [];
        $this->contexts = [];
    }

    public function get(): Application
    {
        return $this->app;
    }

    public function flush(): void
    {
        if (null !== $this->app) {
            $this->app->flush();
            $this->app = null;
        }

        $this->contexts = [];
    }

    public function createApplication(): void
    {
        if (null !== $this->app) {
            throw new RuntimeException('Application is already created.');
        }

        $this->app = require $this->bootstrapPath;

        $this->app->make(Kernel::class)->bootstrap();
        $this->app->make(Request::class)->capture();

        foreach ($this->drivers as $driver) {
            $driver->refreshApplication($this->app);
        }

        foreach ($this->contexts as $context) {
            $context->initLaravelApplication($this->app);
        }

        $this->contexts = [];
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
