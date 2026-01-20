<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Groups\Support;

use Illuminate\Support\Arr;
use Pepperfm\LaravelMacros\Contracts\MacroGroupContract;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;

/**
 * Arr::values(array $array): array
 * Arr::keys(array $array, mixed $filterValue = null, bool $strict = false): array
 * Arr::keyFirst(array $array): int|string|null
 * Arr::keyLast(array $array): int|string|null
 * Arr::flip(array $array): array
 * Arr::combine(array $keys, array $values): array
 * Arr::unique(array $array, int $flags = SORT_STRING): array
 * Arr::reverse(array $array, bool $preserveKeys = false): array
 */
final class ArrNativeMacros implements MacroGroupContract
{
    public function register(MacroManagerContract $macros): void
    {
        $macros->macro(Arr::class, 'values', function (array $array): array {
            return array_values($array);
        });

        $macros->macro(Arr::class, 'keys', function (
            array $array,
            mixed $filterValue = null,
            bool $strict = false,
        ): array {
            $argsCount = func_num_args();

            if ($argsCount === 1) {
                return array_keys($array);
            }
            if ($argsCount === 2) {
                return array_keys($array, $filterValue);
            }

            return array_keys($array, $filterValue, $strict);
        });

        $macros->macro(Arr::class, 'keyFirst', function (array $array): int|string|null {
            return array_key_first($array);
        });

        $macros->macro(Arr::class, 'keyLast', function (array $array): int|string|null {
            return array_key_last($array);
        });

        $macros->macro(Arr::class, 'flip', function (array $array): array {
            return array_flip($array);
        });

        $macros->macro(Arr::class, 'combine', function (array $keys, array $values): array {
            return array_combine($keys, $values);
        });

        $macros->macro(Arr::class, 'unique', function (array $array, int $flags = SORT_STRING): array {
            return array_unique($array, $flags);
        });

        $macros->macro(Arr::class, 'reverse', function (array $array, bool $preserveKeys = false): array {
            return array_reverse($array, $preserveKeys);
        });
    }
}
