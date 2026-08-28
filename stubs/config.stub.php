<?php

declare(strict_types=1);

namespace Illuminate\Config;

/**
 * @method bool toBool(string $key, mixed $default = null, bool $smart = true)
 * @method int|null toInt(string $key, mixed $default = null)
 * @method float toFloat(string $key, mixed $default = null)
 * @method string toString(string $key, mixed $default = null, bool $trim = false)
 * @method array toArray(string $key, array $default = [])
 * @method mixed toEnum(string $key, string $enumClass, mixed $default = null)
 */
final class Repository
{
}

namespace Illuminate\Support\Facades;

/**
 * @method static bool toBool(string $key, mixed $default = null, bool $smart = true)
 * @method static int|null toInt(string $key, mixed $default = null)
 * @method static float toFloat(string $key, mixed $default = null)
 * @method static string toString(string $key, mixed $default = null, bool $trim = false)
 * @method static array toArray(string $key, array $default = [])
 * @method static mixed toEnum(string $key, string $enumClass, mixed $default = null)
 */
final class Config
{
}
