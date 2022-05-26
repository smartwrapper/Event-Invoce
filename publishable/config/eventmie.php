<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | Here you can specify package route Eventmie site and admin panel url prefix
    |
    | prefix : null
    | If prefix is null, then Eventmie site url will be example.com
    |
    | prefix : 'events' -> example.com/events
    | Eventmie site url will be example.com/events
    |
    |
    | admin_prefix : 'admin'
    | Eventmie admin panel url will be example.com/<prefix>/admin
    |
    |
    */
    'route' => [
        'prefix'            => null, 
        'admin_prefix'      => 'admin', // required
    ],


    /*
    |--------------------------------------------------------------------------
    | Multi languages
    |--------------------------------------------------------------------------
    |
    | Remove/Add the languages.
    |
    |
    */
    'locales'       => [
        'en',
        'ar',
        'de',
        'fr',
        'es',
        'hi',
        'it',
        'ja',
        'nl',
        'ru',
        'pt',
        'zh_CN',
        'zh_TW',
    ],

    /*
    |--------------------------------------------------------------------------
    | Detect RTL language
    |--------------------------------------------------------------------------
    |
    | Below are all RTL languages pre defined, and the website direction will 
    | be changed accordingly
    |
    |
    */
    'rtl_langs'        => [
        'ar', // arabic
        'fa', // persian
        'he', // hebrew
        'ms', // malay
        'ur', // urdu
        'ml' // malayalam
    ],

    /*
    |--------------------------------------------------------------------------
    | Default language
    |--------------------------------------------------------------------------
    |
    | Eventmie default language
    |
    |
    */
    'default_lang'  => 'en',


    /**
     * ADVANCED CONFIGURATIONS. INTERNAL USE ONLY.
     * Change at your own risk 
    */

    /*
    |--------------------------------------------------------------------------
    | Database Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify Eventmie database settings
    |
    */
    'database' => [
        'autoload_migrations' => true,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Controllers config
    |--------------------------------------------------------------------------
    |
    | Here you can specify eventmie controller settings
    |
    */

    'controllers' => [
        'namespace' => NULL,
    ],


];


