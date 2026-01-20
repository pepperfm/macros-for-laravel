<?php

declare(strict_types=1);

namespace Illuminate\Support;

use ArrayAccess;

/**
 * @method static bool bool(ArrayAccess|array $array, string|int|null $key, mixed $default = null, bool $smart = true)
 * @method static int int(ArrayAccess|array $array, string|int|null $key, mixed $default = null)
 * @method static float toFloat(ArrayAccess|array $array, string|int|null $key, mixed $default = null)
 * @method static string toString(ArrayAccess|array $array, string|int|null $key, mixed $default = null, bool $trim = false)
 * @method static array toArray(ArrayAccess|array $array, string|int|null $key, array $default = [])
 * @method static mixed toEnum(ArrayAccess|array $array, string|int|null $key, string $enumClass, mixed $default = null)
 * @method static array values(array $array)
 * @method static array keys(array $array, mixed $filterValue = null, bool $strict = false)
 * @method static int|string|null keyFirst(array $array)
 * @method static int|string|null keyLast(array $array)
 * @method static array flip(array $array)
 * @method static array combine(array $keys, array $values)
 * @method static array unique(array $array, int $flags = SORT_STRING)
 * @method static array reverse(array $array, bool $preserveKeys = false)
 */
final class Arr
{
}
