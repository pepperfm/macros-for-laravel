<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Groups\Support;

use Pepperfm\LaravelMacros\Contracts\MacroGroupContract;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Stringable;
use Illuminate\Contracts\Support\Arrayable;
use ArrayAccess;
use BackedEnum;
use InvalidArgumentException;
use Traversable;
use UnitEnum;

/**
 * Arr::bool(ArrayAccess|array $array, string|int|null $key, mixed $default = null, bool $smart = true): bool
 * Arr::int(ArrayAccess|array $array, string|int|null $key, mixed $default = null): int
 * Arr::toFloat(ArrayAccess|array $array, string|int|null $key, mixed $default = null): float
 * Arr::toString(ArrayAccess|array $array, string|int|null $key, mixed $default = null, bool $trim = false): string
 * Arr::toArray(ArrayAccess|array $array, string|int|null $key, array $default = []): array
 * Arr::toEnum(ArrayAccess|array $array, string|int|null $key, string $enumClass, mixed $default = null): mixed
 */
final class ArrCastMacros implements MacroGroupContract
{
    public function register(MacroManagerContract $macros): void
    {
        $macros->macro(Arr::class, 'bool', function (
            ArrayAccess|array $array,
            string|int|null $key,
            mixed $default = null,
            bool $smart = true,
        ): bool {
            $value = Arr::get($array, $key, $default);

            if (!$smart) {
                return (bool) $value;
            }
            if (is_bool($value)) {
                return $value;
            }
            if (is_int($value) || is_float($value)) {
                return (bool) $value;
            }
            if (is_string($value)) {
                $filtered = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

                // If string is recognized as bool, return it
                if ($filtered !== null) {
                    return $filtered;
                }

                // Otherwise avoid (bool)"any string" => true
                return (bool) $default;
            }

            // For everything else use the standard bool cast
            return (bool) $value;
        });

        /*
         * Arr::int($array, 'key', $default = null): int
         */
        $macros->macro(Arr::class, 'int', function (
            ArrayAccess|array $array,
            string|int|null $key,
            mixed $default = null,
        ): int {
            $value = Arr::get($array, $key, $default);

            if (is_int($value)) {
                return $value;
            }
            if (is_bool($value)) {
                return $value ? 1 : 0;
            }
            if (is_float($value)) {
                return (int) $value;
            }
            if (is_string($value)) {
                $v = trim($value);

                if ($v === '') {
                    return (int) ($default ?? 0);
                }
                if (is_numeric($v)) {
                    return (int) $v;
                }
                // Soft parse "42px" -> 42 (remove if you don't want this)
                if (preg_match('/^-?\d+/', $v, $m) === 1) {
                    return (int) $m[0];
                }

                return (int) ($default ?? 0);
            }
            if (is_numeric($value)) {
                return (int) $value;
            }

            return (int) ($default ?? 0);
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
            $value = Arr::get($array, $key, $default);

            if (is_float($value) || is_int($value)) {
                return (float) $value;
            }
            if (is_string($value)) {
                $v = trim($value);

                if ($v === '') {
                    return (float) ($default ?? 0.0);
                }
                if (is_numeric($v)) {
                    return (float) $v;
                }

                return (float) ($default ?? 0.0);
            }
            if (is_numeric($value)) {
                return (float) $value;
            }

            return (float) ($default ?? 0.0);
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
            $value = Arr::get($array, $key, $default);

            if (is_string($value)) {
                return $trim ? trim($value) : $value;
            }
            if ($value === null) {
                return (string) ($default ?? '');
            }
            if (is_scalar($value)) {
                $stringValue = (string) $value;

                return $trim ? trim($stringValue) : $stringValue;
            }
            if ($value instanceof Stringable) {
                $stringValue = (string) $value;

                return $trim ? trim($stringValue) : $stringValue;
            }

            // Avoid turning arrays/objects into "Array"/"Object" without __toString
            return (string) ($default ?? '');
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
            $value = Arr::get($array, $key, $default);

            if (is_array($value)) {
                return $value;
            }
            if ($value instanceof Arrayable) {
                return $value->toArray();
            }
            if ($value instanceof Traversable) {
                return iterator_to_array($value);
            }

            return $default;
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
            if (!enum_exists($enumClass)) {
                throw new InvalidArgumentException(sprintf('Enum class [%s] does not exist.', $enumClass));
            }

            $value = Arr::get($array, $key, $default);

            if ($value instanceof $enumClass) {
                return $value;
            }

            if (is_subclass_of($enumClass, BackedEnum::class)) {
                if (is_string($value) || is_int($value)) {
                    return $enumClass::tryFrom($value) ?? $default;
                }

                return $default;
            }

            if (is_subclass_of($enumClass, UnitEnum::class)) {
                if (is_string($value)) {
                    foreach ($enumClass::cases() as $case) {
                        if ($case->name === $value) {
                            return $case;
                        }
                    }
                }
            }

            return $default;
        });
    }
}
