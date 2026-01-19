# Laravel Macros

Small macro registry for Laravel facades and Macroable classes. It lets you
enable macro groups via config and register everything automatically at boot.

## Install

```bash
composer require pepperfm/macros-for-laravel
```

Laravel auto-discovers the provider:
`Pepperfm\LaravelMacros\Providers\LaravelMacrosServiceProvider`.

## Publish config

```bash
php artisan vendor:publish --tag=macros-for-laravel-config
```

`config/macros-for-laravel.php`:

```php
return [
    'enabled' => env('MACROS_ENABLED', true),
    'profile' => env('MACROS_PROFILE', 'default'),
    'conflicts' => 'throw', // throw | overwrite
    'unreachable' => 'throw', // throw | skip
    'profiles' => [
        'default' => [
            \Pepperfm\LaravelMacros\Groups\Support\ArrCastMacros::class => true,
            \Pepperfm\LaravelMacros\Groups\Support\CollectionFilterMacros::class => false,
            // \Pepperfm\LaravelMacros\Groups\Support\CollectionMacros::class => true,
        ],
        // 'http' => [
        //     \Pepperfm\LaravelMacros\Groups\Facades\ResponseMacros::class => true,
        // ],
    ],
];
```

Switch profiles via env:

```php
MACROS_PROFILE=http
```

You can also use the legacy top-level groups list (no profiles):

```php
'groups' => [
    \Pepperfm\LaravelMacros\Groups\Support\ArrCastMacros::class => true,
];
```

## Built-in macros

### Arr cast helpers

Available when `ArrCastMacros` is enabled:

```php
Arr::bool($array, 'flag');
Arr::int($array, 'count');
Arr::toFloat($array, 'ratio');
Arr::toString($array, 'name', null, true);
Arr::toArray($array, 'items');
Arr::toEnum($array, 'status', Status::class, $default = null);
```

### Collection paginate

Available when `CollectionMacros` is enabled:

```php
collect([1, 2, 3])->paginate(2);
```

### Collection filters

Available when `CollectionFilterMacros` is enabled:

```php
collect([1, null, 2])->filterNotNull();
collect(['', ' ', 'ok', null])->filterNotBlank();
```

## Custom groups

Create a group that implements `Pepperfm\LaravelMacros\Contracts\MacroGroupContract`,
then add it to a profile (or to `groups` in legacy mode). It will be resolved via
the container.
