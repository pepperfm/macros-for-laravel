<?php

declare(strict_types=1);

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Pepperfm\LaravelMacros\Groups\DefaultMacroGroups;
use Pepperfm\LaravelMacros\Groups\Cache\CacheCastMacros;
use Pepperfm\LaravelMacros\Groups\Config\ConfigCastMacros;
use Pepperfm\LaravelMacros\Groups\Support\ArrCastMacros;
use Pepperfm\LaravelMacros\Groups\Support\ArrNativeMacros;
use Pepperfm\LaravelMacros\Groups\Support\CollectionMacros;
use Pepperfm\LaravelMacros\Tests\FallbackGroupsConfigTestCase;

uses(FallbackGroupsConfigTestCase::class);

it('keeps the default macro group list in sync with package defaults', function () {
    expect(DefaultMacroGroups::all())->toBe([
        ArrCastMacros::class,
        ArrNativeMacros::class,
        CacheCastMacros::class,
        ConfigCastMacros::class,
        CollectionMacros::class,
    ]);
});

it('uses default macro groups when no profiles or groups are configured', function () {
    expect(Arr::hasMacro('toBool'))->toBeTrue();
    expect(Arr::hasMacro('values'))->toBeTrue();
    expect(ConfigRepository::hasMacro('toBool'))->toBeTrue();
    expect(Collection::hasMacro('paginate'))->toBeTrue();
});
