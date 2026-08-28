<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Pepperfm\LaravelMacros\Tests\ConfigCastMacrosTestCase;

enum ConfigCastMacroStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
}

uses(ConfigCastMacrosTestCase::class);

it('casts config values from the config helper', function () {
    config()->set('feature.enabled', 'true');
    config()->set('feature.count', '42');
    config()->set('feature.ratio', '3.14');
    config()->set('feature.name', '  Dmitry  ');
    config()->set('feature.filters', ['active' => true]);
    config()->set('feature.status', 'published');

    expect(config()->toBool('feature.enabled'))->toBeTrue();
    expect(config()->toInt('feature.count'))->toBe(42);
    expect(config()->toFloat('feature.ratio'))->toBe(3.14);
    expect(config()->toString('feature.name', trim: true))->toBe('Dmitry');
    expect(config()->toArray('feature.filters'))->toBe(['active' => true]);
    expect(config()->toEnum('feature.status', ConfigCastMacroStatus::class))->toBe(ConfigCastMacroStatus::Published);
});

it('casts defaults from the config helper', function () {
    expect(config()->toString('missing-string', 123))->toBe('123');
    expect(config()->toInt('missing-int', '42'))->toBe(42);
    expect(config()->toInt('missing-nullable-int'))->toBeNull();
    expect(config()->toBool('missing-bool', 'yes'))->toBeTrue();
    expect(config()->toFloat('missing-float', '2.5'))->toBe(2.5);
    expect(config()->toArray('missing-array', ['fallback']))->toBe(['fallback']);
    expect(config()->toEnum('missing-enum', ConfigCastMacroStatus::class, ConfigCastMacroStatus::Draft))
        ->toBe(ConfigCastMacroStatus::Draft);
});

it('returns null for non-numeric config values without a default', function () {
    config()->set('invalid-int', 'invalid');

    expect(config()->toInt('invalid-int'))->toBeNull();
});

it('casts config values from the config facade', function () {
    Config::set('feature.name', 'Dmitry');
    Config::set('feature.count', '7');

    expect(Config::toString('feature.name'))->toBe('Dmitry');
    expect(Config::toInt('feature.count'))->toBe(7);
});
