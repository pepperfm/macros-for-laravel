<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Pepperfm\LaravelMacros\Tests\DisabledConfigTestCase;

uses(DisabledConfigTestCase::class);

it('does not register macros when disabled in config', function () {
    expect(Arr::hasMacro('bool'))->toBeFalse();
    expect(Collection::hasMacro('paginate'))->toBeFalse();
});
