<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Pepperfm\LaravelMacros\MacroManager;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;
use Pepperfm\LaravelMacros\Tests\ConflictConfigTestCase;

uses(ConflictConfigTestCase::class);

it('throws on conflicting macros when conflicts=throw', function () {
    $this->expectException(InvalidArgumentException::class);

    app(MacroManagerContract::class)->register();
});

it('throws when target already has a macro and conflicts=throw', function () {
    Arr::macro('existing', static fn (): string => 'existing');

    $manager = new MacroManager(app(), [], 'throw');
    $manager->macro(Arr::class, 'existing', static fn (): string => 'new');

    $this->expectException(InvalidArgumentException::class);

    $manager->register();
});
