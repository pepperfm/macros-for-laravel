<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Groups\Cache;

use Illuminate\Cache\Repository;
use Pepperfm\LaravelMacros\Contracts\MacroGroupContract;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;
use Pepperfm\LaravelMacros\Support\WithCommonCast;

/*
 * cache()->toBool(string $key, mixed $default = null, bool $smart = true): bool
 * cache()->toInt(string $key, mixed $default = null): int
 * cache()->toFloat(string $key, mixed $default = null): float
 * cache()->toString(string $key, mixed $default = null, bool $trim = false): string
 * cache()->toArray(string $key, array $default = []): array
 * cache()->toEnum(string $key, string $enumClass, mixed $default = null): mixed
 */
final class CacheCastMacros implements MacroGroupContract
{
    use WithCommonCast;

    public function register(MacroManagerContract $macros): void
    {
        $this->registerCommonCastMacros($macros, Repository::class);
    }
}
