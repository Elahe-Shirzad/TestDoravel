<?php

return [

    'user_type' => 'admin',

    'default_guard' => 'admin',

    'application_type' => 'panel', // panel | api

    'cache_storage' => [
        # file , redis , database
        'cache_driver' => env('CACHE_STORAGE_DRIVER', config('cache.default', 'file')),

        'system_ttl' => 1440, #minute
        'user_ttl' => env('SESSION_LIFETIME', 120), // minute
    ],

    'models' => [
        // general
        'setting' => Dornica\Foundation\Settings\Setting::class,
        'user' => config('dornica-access-hub.models.user'),

        // permission
        'role' => Dornica\AccessHub\Authorization\Models\Role::class,
        'permission' => Dornica\AccessHub\Authorization\Models\Permission::class,
        'permission_category' => Dornica\AccessHub\Authorization\Models\PermissionCategory::class,
        'user_role' => Dornica\AccessHub\Authorization\Models\UserRole::class,

        // localization
        'portal' => Dornica\Foundation\Localization\Models\Portal::class,
        'portal_domain' => Dornica\Foundation\Localization\Models\PortalDomain::class,
        'language' => Dornica\Foundation\Localization\Models\Language::class,
        'portal_language' => Dornica\Foundation\Localization\Models\PortalLanguage::class,
        'user_portal' => Dornica\Foundation\Localization\Models\UserPortal::class,
        'portal_status' => Dornica\Foundation\Localization\Models\PortalStatus::class,

        // geo
//        'province' => \App\Models\Province::class,
//        'city' => \App\Models\City::class,
//        'district' => App\Models\District::class,
//        'rural_district' => App\Models\Ruraldistrict::class,
//        'village' => App\Models\Village::class,

        // log
        'log' => Dornica\Foundation\Logger\Models\Log::class,

        // file
        'file' => Dornica\Foundation\FileManager\Models\File::class,
        'file_directory' => Dornica\Foundation\FileManager\Models\FileDirectory::class,
        'file_disk' => Dornica\Foundation\FileManager\Models\FileDisk::class,
        'file_type' => Dornica\Foundation\FileManager\Models\FileType::class,

    ],

    'route_property_collector' => [
        'source' => 'local', // local | database

        /*'allowed_prefixes' => [
            'auth',
            'admins',
            'settings'
        ]*/

        'should_cache_route' => true,
        'should_cache_breadcrumb' => true
    ],

    'settings' => [
        'enable' => true,
    ],

    'localization' => [
        'enable' => false,
        'auto_locale_switch' => false
    ],

    'additional_boolean_scope_fields' => [
//        "is_solved",
//        "can_solve",
    ],

    'log' => [
        'enable' => false,

        'ignore_models' => [
            //App\Models\Log::class,
        ]
    ],

    'encryption' => [
        'method' => 'app_key' // raw | app_key | session_salt
    ],

    'file_manager' => [
        'default_disk_driver' => 'local',
        'php_max_file_size' => ini_get('upload_max_filesize'),
        'max_file_size' => 4096,
        'default_directory_permissions' => 0755,
        'signed_route_expiration' => 10, // minutes
    ],

];
