<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Pepperfm\LaravelMacros\Tests\ArrNativeMacrosTestCase;

uses(ArrNativeMacrosTestCase::class);

it('registers arr native macros', function () {
    expect(Arr::hasMacro('values'))->toBeTrue();
    expect(Arr::hasMacro('keys'))->toBeTrue();
    expect(Arr::hasMacro('keyFirst'))->toBeTrue();
    expect(Arr::hasMacro('keyLast'))->toBeTrue();
    expect(Arr::hasMacro('flip'))->toBeTrue();
    expect(Arr::hasMacro('combine'))->toBeTrue();
    expect(Arr::hasMacro('unique'))->toBeTrue();
    expect(Arr::hasMacro('reverse'))->toBeTrue();
});

it('provides native array helpers', function () {
    expect(Arr::values(['a' => 1, 'b' => 2]))->toBe([1, 2]);

    $keysInput = ['a' => null, 'b' => 1, 'c' => '1'];
    expect(Arr::keys($keysInput))->toBe(['a', 'b', 'c']);
    expect(Arr::keys($keysInput, '1'))->toBe(['b', 'c']);
    expect(Arr::keys($keysInput, '1', true))->toBe(['c']);

    expect(Arr::keyFirst(['a' => 1, 'b' => 2]))->toBe('a');
    expect(Arr::keyLast(['a' => 1, 'b' => 2]))->toBe('b');
    expect(Arr::flip(['a' => 'x', 'b' => 'y']))->toBe(['x' => 'a', 'y' => 'b']);
    expect(Arr::combine(['a', 'b'], [1, 2]))->toBe(['a' => 1, 'b' => 2]);
    expect(Arr::unique(['a', 'a', 'b']))->toBe([0 => 'a', 2 => 'b']);
    expect(Arr::reverse([1, 2]))->toBe([2, 1]);
    expect(Arr::reverse(['a' => 1, 'b' => 2], true))->toBe(['b' => 2, 'a' => 1]);
});
