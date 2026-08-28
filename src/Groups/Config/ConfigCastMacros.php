<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Groups\Config;

use Illuminate\Config\Repository;
use Pepperfm\LaravelMacros\Contracts\MacroGroupContract;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;
use Pepperfm\LaravelMacros\Support\WithCommonCast;

/*
 * config()->toBool(string $key, mixed $default = null, bool $smart = true): bool
 * config()->toInt(string $key, mixed $default = null): ?int
 * config()->toFloat(string $key, mixed $default = null): float
 * config()->toString(string $key, mixed $default = null, bool $trim = false): string
 * config()->toArray(string $key, array $default = []): array
 * config()->toEnum(string $key, string $enumClass, mixed $default = null): mixed
 */
final class ConfigCastMacros implements MacroGroupContract
{
    use WithCommonCast;

    public function register(MacroManagerContract $macros): void
    {
        $this->registerCommonCastMacros($macros, Repository::class);
    }
}
