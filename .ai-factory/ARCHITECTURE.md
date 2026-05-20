# Architecture: Layered Package Architecture

## Обзор

Проект использует простую layered architecture, адаптированную под Laravel package. Это хорошо подходит для небольшой library: публичные contracts фиксируют API, `MacroManager` содержит основную package-логику, macro groups описывают расширения для target classes, а service provider изолирует Laravel integration.

Главная цель архитектуры — не усложнять package лишними слоями, но сохранять четкие границы: reusable logic не должна протекать в provider, а framework integration не должна диктовать устройство macro groups.

## Обоснование решения

- **Тип проекта:** Composer library / Laravel package.
- **Tech stack:** PHP 8.4, Laravel Illuminate 12, Pest/Testbench.
- **Ключевой фактор:** небольшая domain complexity и уже существующие границы `Contracts`, `Groups`, `Provider`, `MacroManager`.
- **Почему не DDD/Clean/Microservices:** package не имеет сложных bounded contexts, persistence layer или независимого deployment.

## Структура каталогов

```text
src/
├── Contracts/
│   ├── MacroGroupContract.php       # Contract for registerable macro groups
│   └── MacroManagerContract.php     # Public macro registration API
├── Facades/
│   └── Macros.php                   # Laravel facade accessor
├── Groups/
│   ├── DefaultMacroGroups.php       # Default group list helper
│   └── Support/
│       ├── ArrCastMacros.php        # Arr cast helpers
│       ├── ArrNativeMacros.php      # Native array helpers
│       └── CollectionMacros.php     # Collection helpers
├── MacroManager.php                 # Core registry and registration policy logic
└── Providers/
    └── LaravelMacrosServiceProvider.php
```

## Dependency Rules

- Разрешено: `Groups/*` зависят от `Contracts/*` и Laravel/Illuminate target classes.
- Разрешено: `MacroManager` зависит от `Contracts/*`, Laravel container и generic target capabilities (`Facade`, `macro()`).
- Разрешено: `LaravelMacrosServiceProvider` зависит от config, container, contracts и concrete `MacroManager`.
- Запрещено: `Contracts/*` зависят от provider, concrete groups или application config.
- Запрещено: macro groups читают package config напрямую; config resolution принадлежит provider.
- Запрещено: provider содержит реализацию конкретных macro methods.

## Взаимодействие слоев

- Service provider читает Laravel config, нормализует profiles/groups/policies и создает singleton `MacroManagerContract`.
- Macro groups реализуют `MacroGroupContract` и вызывают `$macros->macro(...)` или `$macros->macros(...)`.
- `MacroManager` собирает registered macro, применяет conflict/unreachable policies и вызывает `target::macro(...)`.
- Tests через Testbench меняют config сценарии и проверяют публичное behavior на Laravel facades/classes.

## Ключевые принципы

1. **Contracts first:** новые extension points начинаются с contract или существующего `MacroGroupContract`.
2. **Provider stays thin:** service provider только связывает package с Laravel container/config.
3. **Macro groups stay focused:** одна group отвечает за близкий набор macro одного target family.
4. **Config is normalized once:** превращайте пользовательский config в проверенные values перед созданием manager.
5. **Public behavior is tested:** policies, profiles и macro methods должны иметь Pest coverage.

## Code Examples

### Новая macro group

```php
<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Groups\Support;

use Illuminate\Support\Arr;
use Pepperfm\LaravelMacros\Contracts\MacroGroupContract;
use Pepperfm\LaravelMacros\Contracts\MacroManagerContract;

final class ArrExampleMacros implements MacroGroupContract
{
    public function register(MacroManagerContract $macros): void
    {
        $macros->macro(Arr::class, 'example', function (array $array): array {
            return $array;
        });
    }
}
```

### Provider-level config normalization

```php
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
```

## Anti-Patterns

- Не читать `config('macros-for-laravel...')` внутри macro groups.
- Не регистрировать macro напрямую в provider, обходя `MacroManagerContract`.
- Не добавлять silent fallback для неверной конфигурации, если текущая policy ожидает исключение.
- Не менять public macro behavior без обновления tests, README и stubs.
- Не добавлять framework-specific side effects в contracts.
