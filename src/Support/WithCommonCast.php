<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Support;

use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;

/**
 * @mixin \Illuminate\Contracts\Config\Repository
 */
trait WithCommonCast
{
    /**
     * @param class-string $target
     */
    protected function registerCommonCastMacros(MacroManagerContract $macros, string $target): void
    {
        $macros->macro($target, 'toBool', function (
            string $key,
            mixed $default = null,
            bool $smart = true,
        ): bool {
            return ValueCaster::toBool($this->get($key, $default), $default, $smart);
        });

        $macros->macro($target, 'toInt', function (string $key, mixed $default = null): ?int {
            return ValueCaster::toInt($this->get($key, $default), $default);
        });

        $macros->macro($target, 'toFloat', function (string $key, mixed $default = null): float {
            return ValueCaster::toFloat($this->get($key, $default), $default);
        });

        $macros->macro($target, 'toString', function (
            string $key,
            mixed $default = null,
            bool $trim = false,
        ): string {
            return ValueCaster::toString($this->get($key, $default), $default, $trim);
        });

        $macros->macro($target, 'toArray', function (string $key, array $default = []): array {
            return ValueCaster::toArray($this->get($key, $default), $default);
        });

        $macros->macro($target, 'toEnum', function (
            string $key,
            string $enumClass,
            mixed $default = null,
        ): mixed {
            return ValueCaster::toEnum($this->get($key, $default), $enumClass, $default);
        });
    }
}
