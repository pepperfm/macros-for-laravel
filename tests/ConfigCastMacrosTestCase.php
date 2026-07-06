<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Tests;

use Pepperfm\LaravelMacros\Groups\Config\ConfigCastMacros;

class ConfigCastMacrosTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('macros-for-laravel', [
            'enabled' => true,
            'groups' => [
                ConfigCastMacros::class,
            ],
        ]);
    }
}
