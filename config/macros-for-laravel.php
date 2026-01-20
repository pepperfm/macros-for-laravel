<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Enable
    |--------------------------------------------------------------------------
    */
    'enabled' => env('MACROS_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Active Profile
    |--------------------------------------------------------------------------
    | Switch profile via ENV:
    | MACROS_PROFILE=http
    */
    'profile' => env('MACROS_PROFILE', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Policies
    |--------------------------------------------------------------------------
    | conflicts: what to do when the same macro is registered twice
    | unreachable: what to do when target already has a real method
    */
    'conflicts' => 'throw', // throw | overwrite
    'unreachable' => 'throw', // throw | skip

    /*
    |--------------------------------------------------------------------------
    | Macro Profiles
    |--------------------------------------------------------------------------
    | Inside a profile you can specify:
    | 1) a list:
    |    [GroupA::class, GroupB::class]
    |
    | 2) associative with enabled flag:
    |    [GroupA::class => true, GroupB::class => false]
    */
    'profiles' => [
        'default' => [
            // Support helpers
            \Pepperfm\LaravelMacros\Groups\Support\ArrCastMacros::class => true,
            \Pepperfm\LaravelMacros\Groups\Support\ArrNativeMacros::class => true,
            \Pepperfm\LaravelMacros\Groups\Support\CollectionMacros::class => true,

            // Stubs (enable as needed):
            // \Pepperfm\LaravelMacros\Groups\Support\StrMacros::class => true,
            // \Pepperfm\LaravelMacros\Groups\Support\CollectionMacros::class => true,

            // Facades:
            // \Pepperfm\LaravelMacros\Groups\Facades\ResponseMacros::class => true,
            // \Pepperfm\LaravelMacros\Groups\Facades\RouteMacros::class => true,
        ],

        // 'http' => [
        //     \Pepperfm\LaravelMacros\Groups\Facades\ResponseMacros::class => true,
        // ],

        // 'testing' => [
        //     \Pepperfm\LaravelMacros\Groups\Support\CollectionMacros::class => true,
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Legacy Groups (no profiles)
    |--------------------------------------------------------------------------
    | If profiles are not set, this list will be used.
    */
    // 'groups' => [
    //     \Pepperfm\LaravelMacros\Groups\Support\ArrCastMacros::class => true,
    //     \Pepperfm\LaravelMacros\Groups\Support\CollectionMacros::class => false,
    // ],
];
