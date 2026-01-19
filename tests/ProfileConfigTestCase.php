<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Tests;

use Pepperfm\LaravelMacros\Groups\Support\ArrCastMacros;
use Pepperfm\LaravelMacros\Groups\Support\CollectionMacros;

class ProfileConfigTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('macros-for-laravel', [
            'enabled' => true,
            'profile' => 'http',
            'profiles' => [
                'default' => [
                    ArrCastMacros::class => true,
                ],
                'http' => [
                    CollectionMacros::class => true,
                ],
            ],
        ]);
    }
}
