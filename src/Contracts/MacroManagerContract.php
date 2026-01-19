<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Contracts;

interface MacroManagerContract
{
    /**
     * Register a single macro for a target (Facade class or macroable class).
     *
     * @param class-string $target
     */
    public function macro(string $target, string $name, callable $macro): static;

    /**
     * Register multiple macros for a target.
     *
     * @param class-string $target
     * @param array<string, callable> $macros
     */
    public function macros(string $target, array $macros): static;

    /**
     * Define and apply all registered macros.
     */
    public function register(): void;
}
