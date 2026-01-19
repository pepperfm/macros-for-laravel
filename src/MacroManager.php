<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros;

use Illuminate\Contracts\Container\Container;
use Pepperfm\LaravelMacros\Contracts\MacroGroupContract;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;
use Illuminate\Support\Facades\Facade;
use InvalidArgumentException;

class MacroManager implements MacroManagerContract
{
    private const string CONFLICT_THROW = 'throw';
    private const string CONFLICT_OVERWRITE = 'overwrite';
    private const string UNREACHABLE_THROW = 'throw';
    private const string UNREACHABLE_SKIP = 'skip';

    /**
     * @var array<class-string, array<string, callable>>
     */
    private array $macros = [];
    private bool $registered = false;

    /**
     * @param array<class-string<MacroGroupContract>>|null $groups
     */
    public function __construct(
        private readonly Container $app,
        private array $groups = [],
        private string $conflicts = self::CONFLICT_THROW,
        private string $unreachable = self::UNREACHABLE_THROW,
    ) {
        if (!in_array($this->conflicts, [self::CONFLICT_THROW, self::CONFLICT_OVERWRITE], true)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid conflicts policy [%s]. Allowed: throw, overwrite.',
                $this->conflicts
            ));
        }
        if (!in_array($this->unreachable, [self::UNREACHABLE_THROW, self::UNREACHABLE_SKIP], true)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid unreachable policy [%s]. Allowed: throw, skip.',
                $this->unreachable
            ));
        }
    }

    /**
     * External groups (e.g., from another package).
     *
     * @param class-string<MacroGroupContract> $group
     */
    public function addGroup(string $group): self
    {
        $this->groups[] = $group;

        return $this;
    }

    public function macro(string $target, string $name, callable $macro): static
    {
        if (isset($this->macros[$target][$name])) {
            if ($this->conflicts === self::CONFLICT_THROW) {
                throw new InvalidArgumentException(sprintf(
                    'Macro [%s] already registered for target [%s].',
                    $name,
                    $target
                ));
            }
        }

        $this->macros[$target][$name] = $macro;

        return $this;
    }

    public function macros(string $target, array $macros): static
    {
        foreach ($macros as $name => $macro) {
            $this->macro($target, (string) $name, $macro);
        }

        return $this;
    }

    public function register(): void
    {
        if ($this->registered) {
            return;
        }

        // 1) Collect macros from groups
        foreach ($this->groups as $groupClass) {
            $group = $this->app->make($groupClass);
            $group->register($this);
        }

        // 2) Apply macros to targets
        foreach ($this->macros as $target => $macros) {
            $this->registerForTarget($target, $macros);
        }

        $this->registered = true;
    }

    /**
     * @param class-string $target
     * @param array<string, callable> $macros
     */
    private function registerForTarget(string $target, array $macros): void
    {
        $isFacade = is_subclass_of($target, Facade::class);

        if (!$isFacade && !method_exists($target, 'macro')) {
            throw new InvalidArgumentException(sprintf(
                'Target [%s] is not a Facade and does not have a macro() method.',
                $target
            ));
        }

        foreach ($macros as $name => $macro) {
            if (method_exists($target, $name)) {
                if ($this->unreachable === self::UNREACHABLE_THROW) {
                    throw new InvalidArgumentException(sprintf(
                        'Target [%s] already has a real method [%s].',
                        $target,
                        $name
                    ));
                }

                continue;
            }

            $target::macro($name, $macro);
        }
    }
}
