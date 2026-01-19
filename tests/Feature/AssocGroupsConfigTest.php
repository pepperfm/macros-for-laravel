<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Pepperfm\LaravelMacros\Tests\AssocConfigTestCase;

uses(AssocConfigTestCase::class);

it('respects assoc group enable flags', function () {
    expect(Arr::hasMacro('bool'))->toBeTrue();
    expect(Collection::hasMacro('paginate'))->toBeFalse();

    expect(Arr::bool(['flag' => 'true'], 'flag'))->toBeTrue();
});
