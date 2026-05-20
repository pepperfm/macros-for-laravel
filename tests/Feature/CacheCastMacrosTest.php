<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Pepperfm\LaravelMacros\Tests\CacheCastMacrosTestCase;

enum CacheCastMacroStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
}

uses(CacheCastMacrosTestCase::class);

it('casts cached values from the cache helper', function () {
    cache()->put('enabled', 'true');
    cache()->put('count', '42px');
    cache()->put('ratio', '3.14');
    cache()->put('name', '  Dmitry  ');
    cache()->put('filters', ['active' => true]);
    cache()->put('status', 'published');

    expect(cache()->bool('enabled'))->toBeTrue();
    expect(cache()->int('count'))->toBe(42);
    expect(cache()->toFloat('ratio'))->toBe(3.14);
    expect(cache()->toString('name', trim: true))->toBe('Dmitry');
    expect(cache()->toArray('filters'))->toBe(['active' => true]);
    expect(cache()->toEnum('status', CacheCastMacroStatus::class))->toBe(CacheCastMacroStatus::Published);
});

it('casts defaults from the cache helper', function () {
    expect(cache()->toString('missing-string', 123))->toBe('123');
    expect(cache()->int('missing-int', '42'))->toBe(42);
    expect(cache()->bool('missing-bool', 'yes'))->toBeTrue();
    expect(cache()->toFloat('missing-float', '2.5'))->toBe(2.5);
    expect(cache()->toArray('missing-array', ['fallback']))->toBe(['fallback']);
    expect(cache()->toEnum('missing-enum', CacheCastMacroStatus::class, CacheCastMacroStatus::Draft))
        ->toBe(CacheCastMacroStatus::Draft);
});

it('casts cached values from the cache facade and named stores', function () {
    Cache::put('name', 'Dmitry');
    cache()->store('array')->put('count', '7');

    expect(Cache::toString('name'))->toBe('Dmitry');
    expect(cache()->store('array')->int('count'))->toBe(7);
});
