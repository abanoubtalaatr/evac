<?php

return [

    'class_namespace' => 'App\\Http\\Livewire',


    'view_path' => resource_path('views/livewire'),

    'layout' => 'layouts.app',


    'asset_url' => env('LIVEWIRE_URL'),


    'app_url' => null,


    'middleware_group' => 'web',


    'temporary_file_upload' => [
        'disk' => null,        // Example: 'local', 's3'              Default: 'default'
        'rules' => [],       // Example: ['file', 'mimes:png,jpg']  Default: ['required', 'file', 'max:12288'] (12MB)
        'directory' => null,   // Example: 'tmp'                      Default  'livewire-tmp'
        'middleware' => null,  // Example: 'throttle:5,1'             Default: 'throttle:60,1'
        'preview_mimes' => [   // Supported file types for temporary pre-signed file URLs.
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 500, // Max duration (in minutes) before an upload gets invalidated.
    ],

    /*
    |--------------------------------------------------------------------------
    | Manifest File Path
    |--------------------------------------------------------------------------
    |
    | This value sets the path to the Livewire manifest file.
    | The default should work for most cases (which is
    | "<app_root>/bootstrap/cache/livewire-components.php"), but for specific
    | cases like when hosting on Laravel Vapor, it could be set to a different value.
    |
    | Example: for Laravel Vapor, it would be "/tmp/storage/bootstrap/cache/livewire-components.php".
    |
    */

    'manifest_path' => null,

    /*
    |--------------------------------------------------------------------------
    | Back Button Cache
    |--------------------------------------------------------------------------
    |
    | This value determines whether the back button cache will be used on pages
    | that contain Livewire. By disabling back button cache, it ensures that
    | the back button shows the correct state of components, instead of
    | potentially stale, cached data.
    |
    | Setting it to "false" (default) will disable back button cache.
    |
    */

    'back_button_cache' => false,

    /*
    |--------------------------------------------------------------------------
    | Render On Redirect
    |--------------------------------------------------------------------------
    |
    | This value determines whether Livewire will render before it's redirected
    | or not. Setting it to "false" (default) will mean the render method is
    | skipped when redirecting. And "true" will mean the render method is
    | run before redirecting. Browsers bfcache can store a potentially
    | stale view if render is skipped on redirect.
    |
    */

    'render_on_redirect' => false,

];
