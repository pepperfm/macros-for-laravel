<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Tests;

class FallbackGroupsConfigTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('macros-for-laravel', [
            'enabled' => true,
            'profiles' => [],
        ]);
    }
}
