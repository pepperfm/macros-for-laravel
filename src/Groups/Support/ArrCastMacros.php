<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Groups\Support;

use ArrayAccess;
use Illuminate\Support\Arr;
use Pepperfm\LaravelMacros\Contracts\MacroGroupContract;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;
use Pepperfm\LaravelMacros\Support\ValueCaster;

/*
 * Arr::toBool(ArrayAccess|array $array, string|int|null $key, mixed $default = null, bool $smart = true): bool
 * Arr::toInt(ArrayAccess|array $array, string|int|null $key, mixed $default = null): int
 * Arr::toFloat(ArrayAccess|array $array, string|int|null $key, mixed $default = null): float
 * Arr::toString(ArrayAccess|array $array, string|int|null $key, mixed $default = null, bool $trim = false): string
 * Arr::toArray(ArrayAccess|array $array, string|int|null $key, array $default = []): array
 * Arr::toEnum(ArrayAccess|array $array, string|int|null $key, string $enumClass, mixed $default = null): mixed
 */
final class ArrCastMacros implements MacroGroupContract
{
    public function register(MacroManagerContract $macros): void
    {
        $macros->macro(Arr::class, 'toBool', function (
            ArrayAccess|array $array,
            string|int|null $key,
            mixed $default = null,
            bool $smart = true,
        ): bool {
            return ValueCaster::toBool(Arr::get($array, $key, $default), $default, $smart);
        });

        /*
         * Arr::toInt($array, 'key', $default = null): int
         */
        $macros->macro(Arr::class, 'toInt', function (
            ArrayAccess|array $array,
            string|int|null $key,
            mixed $default = null,
        ): int {
            return ValueCaster::toInt(Arr::get($array, $key, $default), $default);
        });

        /*
         * Arr::toFloat($array, 'key', $default = null): float
         * (name is NOT "float" because Arr::float already exists in Laravel)
         */
        $macros->macro(Arr::class, 'toFloat', function (
            ArrayAccess|array $array,
            string|int|null $key,
            mixed $default = null,
        ): float {
            return ValueCaster::toFloat(Arr::get($array, $key, $default), $default);
        });

        /*
         * Arr::toString($array, 'key', $default = null, $trim = false): string
         * (name is NOT "string" because Arr::string already exists in Laravel)
         */
        $macros->macro(Arr::class, 'toString', function (
            ArrayAccess|array $array,
            string|int|null $key,
            mixed $default = null,
            bool $trim = false,
        ): string {
            return ValueCaster::toString(Arr::get($array, $key, $default), $default, $trim);
        });

        /*
         * Arr::toArray($array, 'key', $default = []): array
         * (name is NOT "array" because Arr::array already exists in Laravel)
         */
        $macros->macro(Arr::class, 'toArray', function (
            ArrayAccess|array $array,
            string|int|null $key,
            array $default = [],
        ): array {
            return ValueCaster::toArray(Arr::get($array, $key, $default), $default);
        });

        /*
         * Arr::toEnum($array, 'key', EnumClass::class, $default = null): mixed
         */
        $macros->macro(Arr::class, 'toEnum', function (
            ArrayAccess|array $array,
            string|int|null $key,
            string $enumClass,
            mixed $default = null,
        ): mixed {
            return ValueCaster::toEnum(Arr::get($array, $key, $default), $enumClass, $default);
        });
    }
}
