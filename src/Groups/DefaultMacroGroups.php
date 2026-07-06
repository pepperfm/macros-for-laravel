<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Groups;

use Pepperfm\LaravelMacros\Contracts\MacroGroupContract;
use Pepperfm\LaravelMacros\Groups\Cache\CacheCastMacros;
use Pepperfm\LaravelMacros\Groups\Config\ConfigCastMacros;
use Pepperfm\LaravelMacros\Groups\Support\ArrCastMacros;
use Pepperfm\LaravelMacros\Groups\Support\ArrNativeMacros;
use Pepperfm\LaravelMacros\Groups\Support\CollectionMacros;

final class DefaultMacroGroups
{
    /**
     * @return array<class-string<MacroGroupContract>>
     */
    public static function all(): array
    {
        return [
            ArrCastMacros::class,
            ArrNativeMacros::class,
            CacheCastMacros::class,
            ConfigCastMacros::class,
            CollectionMacros::class,
        ];
    }
}
