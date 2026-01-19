<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Facades;

use Illuminate\Support\Facades\Facade;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract as MacroManagerContract;

/**
 * @method static \Pepperfm\LaravelMacros\Contracts\MacroManagerContract macro(string $target, string $name, callable $macro)
 * @method static \Pepperfm\LaravelMacros\Contracts\MacroManagerContract macros(string $target, array $macros)
 * @method static void register()
 */
final class Macros extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MacroManagerContract::class;
    }
}
