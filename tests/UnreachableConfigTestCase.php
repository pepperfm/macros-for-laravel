<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Tests;

use Pepperfm\LaravelMacros\Tests\Stubs\UnreachableGroup;

class UnreachableConfigTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('macros-for-laravel', [
            'enabled' => false,
            'unreachable' => 'skip',
            'groups' => [
                UnreachableGroup::class,
            ],
        ]);
    }
}
