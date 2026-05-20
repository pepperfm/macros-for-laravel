# AGENTS.md

> Поддерживайте этот файл как короткую карту проекта для AI agents и новых разработчиков. Детальные требования и архитектурные правила живут в `.ai-factory/`.

## Обзор проекта

`pepperfm/macros-for-laravel` — Laravel 12 package для регистрации macro groups через конфигурацию, profiles и политики конфликтов. Подробное описание находится в `.ai-factory/DESCRIPTION.md`.

## Tech Stack

- **Язык:** PHP 8.4
- **Framework/runtime:** Laravel Illuminate 12
- **Тип проекта:** Composer library / Laravel package
- **Тесты:** Pest 4, PHPUnit 12, Orchestra Testbench 10
- **База данных:** не используется
- **ORM:** не используется

## Структура проекта

```text
.
├── composer.json                 # Composer package metadata, dependencies, autoload, scripts
├── config/                       # Publishable Laravel package config
├── src/
│   ├── Contracts/                # Public contracts for macro groups and manager
│   ├── Facades/                  # Laravel facade accessor
│   ├── Groups/                   # Built-in macro groups
│   ├── MacroManager.php          # Macro registry, conflict checks, target registration
│   └── Providers/                # Laravel service provider integration
├── stubs/                        # IDE/PHPStan stubs for registered macro methods
├── tests/
│   ├── Feature/                  # Pest feature tests for package behavior
│   ├── Stubs/                    # Test macro groups
│   └── *TestCase.php             # Testbench config scenarios
├── README.md                     # User-facing package documentation
├── phpunit.xml                   # PHPUnit/Pest test configuration
├── extension.neon                # PHPStan extension include
└── .ai-factory/                  # AI Factory project context and generated guidance
```

## Ключевые точки входа

| Файл | Назначение |
|---|---|
| `src/MacroManager.php` | Собирает macro, проверяет policies и применяет их к target classes. |
| `src/Providers/LaravelMacrosServiceProvider.php` | Подключает config, binding и boot registration в Laravel. |
| `src/Contracts/MacroGroupContract.php` | Contract для новых macro groups. |
| `src/Contracts/MacroManagerContract.php` | Public API регистрации macro. |
| `config/macros-for-laravel.php` | Publishable config с profiles, groups и policies. |
| `tests/Pest.php` | Pest bootstrap для тестов package. |

## Документация

| Документ | Путь | Описание |
|---|---|---|
| README | `README.md` | Пользовательская документация по установке, config и встроенным macro. |
| Project Overview | `.ai/guidelines/00-project-overview.md` | Краткий существующий обзор проекта. |
| DESCRIPTION | `.ai-factory/DESCRIPTION.md` | AI Factory описание stack, возможностей и ограничений. |
| ARCHITECTURE | `.ai-factory/ARCHITECTURE.md` | Практические архитектурные правила проекта. |

## AI Context Files

| Файл | Назначение |
|---|---|
| `AGENTS.md` | Быстрая карта проекта и правила для AI agents. |
| `.ai-factory/config.yaml` | Настройки языка, путей, workflow и git для AI Factory. |
| `.ai-factory/DESCRIPTION.md` | Сводка stack, структуры и требований проекта. |
| `.ai-factory/ARCHITECTURE.md` | Архитектурный паттерн, dependency rules и примеры. |
| `.ai-factory/rules/base.md` | Базовые соглашения, обнаруженные по codebase. |
| `.ai-factory.json` | Состояние установленных локальных AI Factory skills. |

## Правила для агентов

- Не склеивайте независимые shell-команды через `&&`, если их результат важен для следующего шага.
  - Неверно: `git checkout master && git pull`
  - Верно: сначала `git checkout master`, затем `git pull origin master`
- Не изменяйте runtime code во время `$aif` setup: этот этап отвечает только за контекст, skills, правила и архитектурные артефакты.
- Перед изменением package API проверьте `README.md`, `stubs/` и feature tests: они должны оставаться согласованными.
- Сохраняйте `declare(strict_types=1);`, typed signatures и текущий Laravel package style.
