# Описание проекта

## Обзор

`pepperfm/macros-for-laravel` — небольшой Laravel 12 package для регистрации групп macro через конфигурацию. Пакет подключает macro к Laravel facades и Macroable-классам при boot приложения, поддерживает профили, политики конфликтов и встроенные helpers для `Arr` и `Collection`.

## Обнаруженный стек

- **Язык:** PHP 8.4
- **Framework/runtime:** Laravel Illuminate 12 (`illuminate/support`, `illuminate/pagination`)
- **Тип проекта:** Composer library / Laravel package
- **Тесты:** Pest 4, PHPUnit 12, Orchestra Testbench 10
- **Статический анализ:** PHPStan extension через `extension.neon`
- **Пакетная интеграция:** Laravel auto-discovery provider и facade alias в `composer.json`

## Основные возможности

- Регистрация macro-групп через `MacroManager`.
- Поддержка конфигурационных profiles и legacy `groups`.
- Политики `conflicts` (`throw`, `overwrite`) и `unreachable` (`throw`, `skip`).
- Встроенные `Arr` cast helpers: `bool`, `int`, `toFloat`, `toString`, `toArray`, `toEnum`.
- Встроенные native array helpers для `Arr`.
- `Collection` helpers: `paginate`, `filterNotNull`, `filterNotBlank`.
- Publishable Laravel config: `config/macros-for-laravel.php`.

## Структура проекта

- `src/Contracts/` — публичные contracts для macro group и manager.
- `src/Groups/` — встроенные macro groups.
- `src/Providers/` — Laravel service provider, config merge, container binding и boot registration.
- `src/Facades/` — facade accessor для manager contract.
- `config/` — publishable package config.
- `stubs/` — PHPStan/IDE stubs для macro methods.
- `tests/` — Pest feature tests и Testbench test cases.

## Архитектурные заметки

Проект устроен как небольшой layered Laravel package: публичные contracts отделяют API регистрации от реализации, provider связывает package с Laravel container/config, а macro groups содержат конкретные additions для целевых классов.

Ключевой инвариант: package code не должен зависеть от приложения-потребителя сильнее, чем через Laravel container/config и Illuminate contracts/facades. Новые macro groups должны реализовывать `MacroGroupContract` и регистрировать методы через `MacroManagerContract`.

## Нефункциональные требования

- **Совместимость:** сохранять PHP 8.4 и Laravel Illuminate 12 constraints из `composer.json`.
- **Ошибки:** конфигурационные ошибки и нарушения политик выражать через `InvalidArgumentException` с понятным сообщением.
- **Тестирование:** изменения behavior покрывать Pest tests на уровне package/Testbench.
- **Документация:** публичные macro и config behavior отражать в `README.md` и stubs при изменении API.
- **Технические термины:** сохранять имена Laravel/PHP сущностей, package paths и method names на английском.

## Архитектура

Подробные архитектурные правила находятся в `.ai-factory/ARCHITECTURE.md`.

**Паттерн:** Layered Package Architecture
