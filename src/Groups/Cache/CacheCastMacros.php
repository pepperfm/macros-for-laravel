<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Groups\Cache;

use Illuminate\Cache\Repository;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Stringable;
use Pepperfm\LaravelMacros\Contracts\MacroGroupContract;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;

/**
 * cache()->bool(string $key, mixed $default = null, bool $smart = true): bool
 * cache()->int(string $key, mixed $default = null): int
 * cache()->toFloat(string $key, mixed $default = null): float
 * cache()->toString(string $key, mixed $default = null, bool $trim = false): string
 * cache()->toArray(string $key, array $default = []): array
 * cache()->toEnum(string $key, string $enumClass, mixed $default = null): mixed
 */
final class CacheCastMacros implements MacroGroupContract
{
    public function register(MacroManagerContract $macros): void
    {
        $macros->macro(Repository::class, 'bool', function (
            string $key,
            mixed $default = null,
            bool $smart = true,
        ): bool {
            /** @var Repository $this */

            return CacheCastMacros::castBool($this->get($key, $default), $default, $smart);
        });

        $macros->macro(Repository::class, 'int', function (string $key, mixed $default = null): int {
            /** @var Repository $this */

            return CacheCastMacros::castInt($this->get($key, $default), $default);
        });

        $macros->macro(Repository::class, 'toFloat', function (string $key, mixed $default = null): float {
            /** @var Repository $this */

            return CacheCastMacros::castFloat($this->get($key, $default), $default);
        });

        $macros->macro(Repository::class, 'toString', function (
            string $key,
            mixed $default = null,
            bool $trim = false,
        ): string {
            /** @var Repository $this */

            return CacheCastMacros::castString($this->get($key, $default), $default, $trim);
        });

        $macros->macro(Repository::class, 'toArray', function (string $key, array $default = []): array {
            /** @var Repository $this */

            return CacheCastMacros::castArray($this->get($key, $default), $default);
        });

        $macros->macro(Repository::class, 'toEnum', function (
            string $key,
            string $enumClass,
            mixed $default = null,
        ): mixed {
            /** @var Repository $this */

            return CacheCastMacros::castEnum($this->get($key, $default), $enumClass, $default);
        });
    }

    public static function castBool(mixed $value, mixed $default = null, bool $smart = true): bool
    {
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
            if ($filtered !== null) {
                return $filtered;
            }

            return (bool) $default;
        }

        return (bool) $value;
    }

    public static function castInt(mixed $value, mixed $default = null): int
    {
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
            if (preg_match('/^-?\d+/', $v, $m) === 1) {
                return (int) $m[0];
            }

            return (int) ($default ?? 0);
        }
        if (is_numeric($value)) {
            return (int) $value;
        }

        return (int) ($default ?? 0);
    }

    public static function castFloat(mixed $value, mixed $default = null): float
    {
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
    }

    public static function castString(mixed $value, mixed $default = null, bool $trim = false): string
    {
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

        return (string) ($default ?? '');
    }

    public static function castArray(mixed $value, array $default = []): array
    {
        if (is_array($value)) {
            return $value;
        }
        if ($value instanceof Arrayable) {
            return $value->toArray();
        }
        if ($value instanceof \Traversable) {
            return iterator_to_array($value);
        }

        return $default;
    }

    /**
     * @param class-string $enumClass
     */
    public static function castEnum(mixed $value, string $enumClass, mixed $default = null): mixed
    {
        if (!enum_exists($enumClass)) {
            throw new \InvalidArgumentException(sprintf('Enum class [%s] does not exist.', $enumClass));
        }
        if ($value instanceof $enumClass) {
            return $value;
        }

        if (is_subclass_of($enumClass, \BackedEnum::class)) {
            if (is_string($value) || is_int($value)) {
                return $enumClass::tryFrom($value) ?? $default;
            }

            return $default;
        }

        if (is_subclass_of($enumClass, \UnitEnum::class)) {
            if (is_string($value)) {
                foreach ($enumClass::cases() as $case) {
                    if ($case->name === $value) {
                        return $case;
                    }
                }
            }
        }

        return $default;
    }
}
