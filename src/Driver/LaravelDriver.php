<?php

namespace Cevinio\Behat\Driver;

use Behat\Mink\Driver\BrowserKitDriver;
use Illuminate\Foundation\Application;
use Cevinio\Behat\ServiceContainer\LaravelFactory;
use Symfony\Component\HttpKernel\HttpKernelBrowser;

final class LaravelDriver extends BrowserKitDriver
{
    public function __construct(
        LaravelFactory $factory,
        private readonly ?string $baseUrl = null,
    ) {
        $factory->registerDriver($this);
    }

    /** @internal */
    public function refreshApplication(Application $app): void
    {
        if (true === $this->isStarted()) {
            $this->stop();
        }

        parent::__construct(new HttpKernelBrowser($app), $this->baseUrl);
    }
}
