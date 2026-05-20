# Базовые правила проекта

> Автоматически определенные правила по текущему коду. Редактируйте при изменении соглашений проекта.

## Именование

- **Файлы:** PHP classes используют PascalCase и PSR-4 namespace `Pepperfm\LaravelMacros\...`; feature tests заканчиваются на `Test.php`, Testbench cases — на `TestCase.php`.
- **Переменные:** camelCase (`$groupClass`, `$profileGroups`, `$stringValue`).
- **Функции и методы:** camelCase; публичные API методы короткие и глагольные (`addGroup`, `macro`, `macros`, `register`).
- **Классы и interfaces:** PascalCase; contracts заканчиваются на `Contract`, macro groups — на `Macros`.
- **Константы:** UPPER_SNAKE_CASE для private policy constants.

## Структура модулей

- `src/Contracts/` содержит только публичные interfaces.
- `src/MacroManager.php` отвечает за сбор macro, проверку конфликтов и применение к targets.
- `src/Providers/` связывает package с Laravel: merge config, singleton binding, publish config, boot registration.
- `src/Groups/Support/` содержит встроенные groups для Illuminate support classes.
- `src/Facades/` содержит Laravel facade wrapper.
- `tests/Feature/` проверяет пользовательское behavior; shared setup живет в `tests/*TestCase.php`.

## Обработка ошибок

- Для неверной конфигурации, конфликтов macro и unreachable target methods используется `InvalidArgumentException`.
- Сообщения об ошибках должны включать проблемное значение или class-string, когда это помогает пользователю исправить config.
- Guard clauses предпочтительнее вложенных условий для раннего выхода (`enabled=false`, повторная регистрация, disabled groups).

## Логирование

- Runtime logging в package code сейчас отсутствует.
- Не добавляйте `ray()`, `Log::...` или debug output в production code без явной задачи.
- Для диагностики в тестах используйте assertions и Testbench setup вместо отладочного вывода.

## Тесты

- Основной запуск: `vendor/bin/pest` или `composer test`.
- Feature tests используют Pest syntax `uses(...); it(...); expect(...);`.
- Конфигурационные сценарии изолируются через отдельные Testbench cases с `getEnvironmentSetUp`.
- При добавлении macro обновляйте tests, README и соответствующий stub в `stubs/`.

## Стиль PHP/Laravel

- Каждый PHP file начинается с `declare(strict_types=1);`.
- Используйте typed properties, typed returns и constructor property promotion, где это уже соответствует коду.
- Для работы с Laravel config внутри provider сохраняйте текущий стиль: normalize входящих config values перед передачей в manager.
- Новые macro groups должны реализовывать `MacroGroupContract` и регистрировать методы только через `MacroManagerContract`.
