<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Pepperfm\LaravelMacros\Tests\ProfileConfigTestCase;

uses(ProfileConfigTestCase::class);

it('loads groups from the active profile', function () {
    expect(Arr::hasMacro('bool'))->toBeFalse();
    expect(Collection::hasMacro('paginate'))->toBeTrue();
});
