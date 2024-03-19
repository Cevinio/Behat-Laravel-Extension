<?php

namespace Cevinio\Behat\Context\Argument;

use Cevinio\Behat\ServiceContainer\LaravelFactory;
use ReflectionClass;
use Behat\Behat\Context\Argument\ArgumentResolver;

final class LaravelArgumentResolver implements ArgumentResolver
{
    public function __construct(
        private readonly LaravelFactory $factory,
    ) {
    }

    public function resolveArguments(ReflectionClass $classReflection, array $arguments): array
    {
        return array_map(function ($argument) {
            if (true === is_string($argument) && '' !== $argument && '@' === $argument[0]) {
                return $this->factory->get()->make(substr($argument, 1));
            }

            return $argument;
        }, $arguments);
    }
}
