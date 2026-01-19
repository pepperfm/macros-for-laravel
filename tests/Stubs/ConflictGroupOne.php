<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Tests\Stubs;

use Illuminate\Support\Arr;
use Pepperfm\LaravelMacros\Contracts\MacroGroupContract;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;

final class ConflictGroupOne implements MacroGroupContract
{
    public function register(MacroManagerContract $macros): void
    {
        $macros->macro(Arr::class, 'dup', static fn (): string => 'one');
    }
}
