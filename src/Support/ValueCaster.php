<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Stringable;
use InvalidArgumentException;
use Traversable;
use BackedEnum;
use UnitEnum;

final class ValueCaster
{
    public static function toBool(mixed $value, mixed $default = null, bool $smart = true): bool
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

    public static function toInt(mixed $value, mixed $default = null): ?int
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
                return $default === null ? null : (int) $default;
            }
            if (is_numeric($v)) {
                return (int) $v;
            }

            return $default === null ? null : (int) $default;
        }
        if (is_numeric($value)) {
            return (int) $value;
        }

        return $default === null ? null : (int) $default;
    }

    public static function toFloat(mixed $value, mixed $default = null): float
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

    public static function toString(mixed $value, mixed $default = null, bool $trim = false): string
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

    public static function toArray(mixed $value, array $default = []): array
    {
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
    }

    /**
     * @param class-string $enumClass
     */
    public static function toEnum(mixed $value, string $enumClass, mixed $default = null): mixed
    {
        if (!enum_exists($enumClass)) {
            throw new InvalidArgumentException(sprintf('Enum class [%s] does not exist.', $enumClass));
        }
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
    }
}
