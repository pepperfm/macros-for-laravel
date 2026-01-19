# Project Overview — A Laravel 12 package that registers macro groups via config (profiles/groups) and applies them to facades and Macroable classes. By default it provides soft‑casts for `Arr` and lets you enable additional groups as needed.

## Tech Stack — PHP 8.4, Laravel Illuminate (support/pagination), Orchestra Testbench, Pest, PHPUnit

## Main Features — key features and where they live
- Macro registry and target application: `src/MacroManager.php`
- Service provider that loads profiles and policies: `src/Providers/LaravelMacrosServiceProvider.php`
- Profile/policy configuration: `config/macros-for-laravel.php`
- `ArrCastMacros` group (soft‑cast + `toEnum`): `src/Groups/Support/ArrCastMacros.php`
- Collection filters: `src/Groups/Support/CollectionFilterMacros.php`
- Tests (Testbench + Pest): `tests`

## Architecture / Structure — project layout
- `src/` — package source (contracts, provider, manager, macro groups)
- `config/` — publishable config for profiles/policies
- `tests/` — Pest tests with Testbench
- `README.md` — user documentation

## Development — common commands
- Install dependencies: `composer install`
- Run tests: `vendor/bin/pest`
