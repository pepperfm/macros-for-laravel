<?php

declare(strict_types=1);

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Pepperfm\LaravelMacros\Tests\TestCase;

uses(TestCase::class);

it('registers arr cast macros from list config', function () {
    expect(Arr::hasMacro('bool'))->toBeTrue();
    expect(Arr::hasMacro('int'))->toBeTrue();
    expect(Arr::hasMacro('toFloat'))->toBeTrue();
    expect(Arr::hasMacro('toString'))->toBeTrue();
    expect(Arr::hasMacro('toArray'))->toBeTrue();

    expect(Arr::bool(['flag' => 'true'], 'flag'))->toBeTrue();
    expect(Arr::bool(['flag' => 'nope'], 'flag'))->toBeFalse();
    expect(Arr::int(['count' => '42'], 'count'))->toBe(42);
    expect(Arr::toFloat(['ratio' => '3.14'], 'ratio'))->toBe(3.14);
    expect(Arr::toString(['name' => 100], 'name'))->toBe('100');
    expect(Arr::toArray(['items' => [1, 2]], 'items'))->toBe([1, 2]);
});

it('registers collection paginate macro from list config', function () {
    expect(Collection::hasMacro('paginate'))->toBeTrue();

    $paginator = collect([1, 2, 3, 4, 5])->paginate(2);

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class);
    expect($paginator->items())->toBe([1, 2]);
    expect($paginator->total())->toBe(5);
    expect($paginator->perPage())->toBe(2);
});
