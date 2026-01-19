<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Tests;

use Pepperfm\LaravelMacros\Tests\Stubs\ConflictGroupOne;
use Pepperfm\LaravelMacros\Tests\Stubs\ConflictGroupTwo;

class ConflictConfigTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('macros-for-laravel', [
            'enabled' => false,
            'conflicts' => 'throw',
            'groups' => [
                ConflictGroupOne::class,
                ConflictGroupTwo::class,
            ],
        ]);
    }
}
