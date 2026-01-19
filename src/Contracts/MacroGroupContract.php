<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Contracts;

interface MacroGroupContract
{
    public function register(MacroManagerContract $macros): void;
}
