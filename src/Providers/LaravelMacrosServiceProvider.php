<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;
use Pepperfm\LaravelMacros\Contracts\MacroGroupContract;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;
use Pepperfm\LaravelMacros\MacroManager;

final class LaravelMacrosServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/macros-for-laravel.php',
            'macros-for-laravel'
        );

        $this->app->singleton(MacroManagerContract::class, function (Container $app) {
            $cfg = (array) $app['config']->get('macros-for-laravel', []);
            $groups = $this->normalizeGroups($this->resolveGroupsConfig($cfg));
            $conflicts = $this->normalizePolicy($cfg['conflicts'] ?? 'throw', 'conflicts', ['throw', 'overwrite']);
            $unreachable = $this->normalizePolicy($cfg['unreachable'] ?? 'throw', 'unreachable', ['throw', 'skip']);

            return new MacroManager($app, $groups, $conflicts, $unreachable);
        });
    }

    public function boot(MacroManagerContract $macros): void
    {
        // Publish config
        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__ . '/../../config/macros-for-laravel.php' => config_path('macros-for-laravel.php'),
            ], 'macros-for-laravel-config');
        }

        if (!config('macros-for-laravel.enabled', true)) {
            return;
        }

        $macros->register();
    }

    /**
     * @param array<int|string, mixed> $groups
     *
     * @return array<class-string<MacroGroupContract>>
     */
    private function normalizeGroups(array $groups): array
    {
        $result = [];

        foreach ($groups as $key => $value) {
            $class = is_int($key) ? $value : $key;
            $enabled = is_int($key) || $value;

            if (!$enabled) {
                continue;
            }
            if (!is_string($class) || $class === '') {
                throw new InvalidArgumentException('Invalid macro group entry in config (must be class-string).');
            }
            if (!is_subclass_of($class, MacroGroupContract::class)) {
                throw new InvalidArgumentException(sprintf(
                    'Macro group [%s] must implement %s.',
                    $class,
                    MacroGroupContract::class
                ));
            }

            $result[] = $class;
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $cfg
     *
     * @return array<int|string, mixed>
     */
    private function resolveGroupsConfig(array $cfg): array
    {
        $profiles = $cfg['profiles'] ?? null;
        $profile = $cfg['profile'] ?? 'default';

        if (is_array($profiles) && $profiles !== []) {
            $profileName = is_string($profile) && $profile !== '' ? $profile : 'default';
            $profileGroups = $profiles[$profileName] ?? $profiles['default'] ?? null;

            if ($profileGroups !== null) {
                if (!is_array($profileGroups)) {
                    throw new InvalidArgumentException('Macro profile groups must be an array.');
                }

                return $profileGroups;
            }
        }

        $groups = $cfg['groups'] ?? [];

        return is_array($groups) ? $groups : (array) $groups;
    }

    /**
     * @param mixed $value
     * @param array<int, string> $allowed
     */
    private function normalizePolicy(mixed $value, string $key, array $allowed): string
    {
        $policy = is_string($value) ? $value : '';

        if (!in_array($policy, $allowed, true)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid %s policy [%s]. Allowed: %s.',
                $key,
                is_scalar($value) ? (string) $value : gettype($value),
                implode(', ', $allowed)
            ));
        }

        return $policy;
    }
}
