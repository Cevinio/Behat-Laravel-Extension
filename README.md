This extension offers an incredibly simple (and fast) way to begin testing and driving your Laravel applications with Behat. Some benefits include:

- **Fast:** It doesn't depend on anything like Goutte, so it offers a super-fast way to test your UI. You don't even need to setup a host to run your tests.
- **Refresh:** Laravel is automatically rebooted before each scenario (so nothing like user sessions will be persisted).
- **Environments:** Specifying custom environment files (like the `.env` one) for different app environments is a little tricky in Laravel. This extension handles that for you automatically.
- **Access Laravel:** You instantly have access to Laravel (things like facades and such) from your `FeatureContext` file.

> This repository is forked from [laracasts/Behat-Laravel-Extension](https://github.com/laracasts/Behat-Laravel-Extension) because it was no longer being maintained.  
> Credits go to [Jeffrey Way](https://github.com/JeffreyWay) for originally creating this extension.

# 1. Install

Require the package as a dev dependency via Composer.

    composer require --dev cevinio/behat-laravel-extension

This will also pull in Behat and Mink.

# 2. Create the Behat.yml Configuration File

Next, within your project root, create a `behat.yml` file, and add:

```
default:
    extensions:
        Cevinio\Behat:
            # bootstrap_path: ~
            # env_path: ~
        Behat\MinkExtension:
            default_session: laravel
            laravel: ~
            files_path: "%paths.base%/tests/"
```

Here, is where we reference the Laravel extension, and tell Behat to use it as our default session.

You may pass an optional parameter, `env_path` (currently commented out above) to specify the name of the environment file that should be referenced from your tests.
The default is empty and will keep Laravel standard behavior to look for `.env`. If you want a special environment file you can set it for example to `.env.behat`.  This file should, like the standard `.env` file in your project root, contain any special environment variables  for your tests (such as a special acceptance test-specific database).

It is also possible to specify an alternative bootstrap file by setting the optional parameter `bootstrap_path` (this defaults to `bootstrap/app.php`). Behat's `%paths.base%` will always be prepended to this path.

# 3. Setting up FeatureContext

Run, from the root of your app

~~~
vendor/bin/behat --init
~~~

It should set 

~~~
features/bootstrap/FeatureContext.php
~~~ 

At this point you should set it to extend `MinkContext` and implement `LaravelAwareContext`.
You can use the `LaravelAware` trait to get access to the `Application` via `$this->app()`.

~~~

<?php

use Behat\Behat\EventDispatcher\Event\BeforeScenarioTested;
use Behat\MinkExtension\Context\MinkContext;
use Cevinio\Behat\Context\LaravelAware;
use Cevinio\Behat\Context\LaravelAwareContext;
use Illuminate\Contracts\Foundation\Application;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements LaravelAwareContext
{
    use LaravelAware;

    public function bootstrapLaravelEnvironment(BeforeScenarioTested $event): array
    {
        // This method is optional, if implemented the returned array is set in the Laravel environment for access via env().
        // This allows modification of configuration values on a per-scenario basis.
    }

    public function bootstrapLaravelApplication(Application $app, BeforeScenarioTested $event): void
    {
        // This method is optional. It allows access to the Application right after it was created.
        // This allows for example to call $app->bind() before any @BeforeScenario hook is called. 
    }
}
~~~ 


# 4. Write Some Features

You're all set to go! Start writing some features.

> Note: if you want to leverage some of the Mink helpers in your `FeatureContext` file, then be sure to extend `Behat\MinkExtension\Context\MinkContext`.
