<?php

declare(strict_types=1);

use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;
use Pepperfm\LaravelMacros\Tests\ConflictConfigTestCase;

uses(ConflictConfigTestCase::class);

it('throws on conflicting macros when conflicts=throw', function () {
    $this->expectException(InvalidArgumentException::class);

    app(MacroManagerContract::class)->register();
});
