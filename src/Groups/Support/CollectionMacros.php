<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Groups\Support;

use Pepperfm\LaravelMacros\Contracts\MacroGroupContract;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class CollectionMacros implements MacroGroupContract
{
    public function register(MacroManagerContract $macros): void
    {
        $macros->macro(Collection::class, 'paginate', function (
            int $perPage,
            ?int $total = null,
            ?int $page = null,
            string $pageName = 'page'
        ): LengthAwarePaginator {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            /** @var LengthAwarePaginator $this */

            return new LengthAwarePaginator(
                items: $this->forPage($page, $perPage)->values(),
                total: $total ?: $this->count(),
                perPage: $perPage,
                currentPage: $page,
                options: [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        $macros->macro(Collection::class, 'filterNotNull', function (): Collection {
            /** @var Collection $this */

            return $this->filter(static fn ($value) => $value !== null)->values();
        });

        $macros->macro(Collection::class, 'filterNotBlank', function (): Collection {
            /** @var Collection $this */

            return $this->filter(static fn ($value) => filled($value))->values();
        });
    }
}
