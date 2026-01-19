<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Pepperfm\LaravelMacros\Tests\TestCase;

enum Status: string
{
    case Draft = 'draft';
    case Published = 'published';
}

enum Flag
{
    case Alpha;
    case Beta;
}

uses(TestCase::class);

it('casts backed enums via toEnum', function () {
    expect(Arr::toEnum(['status' => 'draft'], 'status', Status::class))->toBe(Status::Draft);
    expect(Arr::toEnum(['status' => 'missing'], 'status', Status::class))->toBeNull();
    expect(Arr::toEnum(['status' => Status::Published], 'status', Status::class))->toBe(Status::Published);
});

it('casts unit enums via toEnum by name', function () {
    expect(Arr::toEnum(['flag' => 'Alpha'], 'flag', Flag::class))->toBe(Flag::Alpha);
    expect(Arr::toEnum(['flag' => 'Gamma'], 'flag', Flag::class, Flag::Beta))->toBe(Flag::Beta);
});
