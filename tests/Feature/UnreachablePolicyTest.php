<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;
use Pepperfm\LaravelMacros\Tests\UnreachableConfigTestCase;

uses(UnreachableConfigTestCase::class);

it('skips unreachable macros when unreachable=skip', function () {
    app(MacroManagerContract::class)->register();

    expect(Arr::hasMacro('get'))->toBeFalse();
});
