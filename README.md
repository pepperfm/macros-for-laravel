# Laravel Macros

Small macro registry for Laravel facades and Macroable classes. It lets you
enable macro groups via config and register everything automatically at boot.

## Install

```bash
composer r pepperfm/macros-for-laravel
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
            \Pepperfm\LaravelMacros\Groups\Support\ArrNativeMacros::class => true,
            \Pepperfm\LaravelMacros\Groups\Cache\CacheCastMacros::class => true,
            \Pepperfm\LaravelMacros\Groups\Config\ConfigCastMacros::class => true,
            \Pepperfm\LaravelMacros\Groups\Support\CollectionMacros::class => true,
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
Arr::toBool($array, 'flag');
Arr::toInt($array, 'count');
Arr::toFloat($array, 'ratio');
Arr::toString($array, 'name', null, true);
Arr::toArray($array, 'items');
Arr::toEnum($array, 'status', Status::class, $default = null);
```

### Arr native array helpers

Available when `ArrNativeMacros` is enabled:

```php
Arr::values($array);
Arr::keys($array);
Arr::keyFirst($array);
Arr::keyLast($array);
Arr::flip($array);
Arr::combine(['a', 'b'], [1, 2]);
Arr::unique(['a', 'a', 'b']);
Arr::reverse([1, 2, 3]);
```

### Collection paginate

Available when `CollectionMacros` is enabled:

```php
collect([1, 2, 3])->paginate(2);
```

### Collection filters

Available when `CollectionMacros` is enabled:

```php
collect([1, null, 2])->filterNotNull();
collect(['', ' ', 'ok', null])->filterNotBlank();
```

### Cache typed getters

Available when `CacheCastMacros` is enabled:

```php
cache()->toBool('enabled', false);
cache()->toInt('count', 0);
cache()->toFloat('ratio', 0.0);
cache()->toString('name', 'guest', true);
cache()->toArray('filters', []);
cache()->toEnum('status', Status::class, Status::Draft);

Cache::toString('name', 'guest');
cache()->store('redis')->toInt('count', 0);
```

`cache('name', 'guest')->toString()` is not supported because Laravel's `cache()`
helper returns the raw cached value when a key is passed.

### Config typed getters

Available when `ConfigCastMacros` is enabled:

```php
config()->toBool('features.enabled', false);
config()->toInt('features.count', 0);
config()->toFloat('features.ratio', 0.0);
config()->toString('app.name', 'Laravel', true);
config()->toArray('features.filters', []);
config()->toEnum('features.status', Status::class, Status::Draft);

Config::toString('app.name', 'Laravel');
```

`config('app.name', 'Laravel')->toString()` is not supported because Laravel's
`config()` helper returns the raw config value when a key is passed.

## Custom groups

Create a group that implements `Pepperfm\LaravelMacros\Contracts\MacroGroupContract`,
then add it to a profile (or to `groups` in legacy mode). It will be resolved via
the container.
