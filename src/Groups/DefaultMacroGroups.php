<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Groups;

use Pepperfm\LaravelMacros\Contracts\MacroGroupContract;
use Pepperfm\LaravelMacros\Groups\Support\ArrCastMacros;
use Pepperfm\LaravelMacros\Groups\Support\CollectionMacros;

final class DefaultMacroGroups
{
    /**
     * @return array<class-string<MacroGroupContract>>
     */
    public static function all(): array
    {
        return [
            CollectionMacros::class,

            ArrCastMacros::class,
        ];
    }
}
