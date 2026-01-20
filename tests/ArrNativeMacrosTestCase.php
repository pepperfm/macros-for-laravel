<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Tests;

use Pepperfm\LaravelMacros\Groups\Support\ArrNativeMacros;

class ArrNativeMacrosTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('macros-for-laravel', [
            'enabled' => true,
            'groups' => [
                ArrNativeMacros::class,
            ],
        ]);
    }
}
