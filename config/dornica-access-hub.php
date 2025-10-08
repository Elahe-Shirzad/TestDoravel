<?php

return [
    'authorization' => [
        'enable' => false,
    ],

    'authentication' => [
        'login_field' => 'national_code',

        'otp' => [
            // Supported types: email, mobile
            'provider_type' => 'mobile',

            'code_length' => 6,

            'duration' => 120, // seconds
        ],

        'password' => [
            'min_length' => 8,
            'max_length' => 32,

            'reset_password' => [
                'daily_limit' => 3,
                'cooldown' => 5,
            ]
        ],

        'providers_data' => [
            'email' => [
                'mailer' => 'smtp',
                'host' => 'mail.dornica.net',
                'port' => 587,
                'username' => '',
                'password' => '',
                'encryption' => 'tls',
                'from_address' => "",
                'from_name' => config('app.name'),
            ],

            'mobile' => [],
        ],

        'slider_images' => []
    ],

    'models' => [
        // Authorization
        'role' => Dornica\AccessHub\Authorization\Models\Role::class,
        'permission' => Dornica\AccessHub\Authorization\Models\Permission::class,
        'permission_category' => Dornica\AccessHub\Authorization\Models\PermissionCategory::class,
        'role_permission' => Dornica\AccessHub\Authorization\Models\RolePermission::class,
        'user_role' => Dornica\AccessHub\Authorization\Models\UserRole::class,
        'user_role_permission' => Dornica\AccessHub\Authorization\Models\UserRolePermission::class,

        // Authentication
        'user' => Dornica\AccessHub\Authentication\Models\User::class,
        'user_activation' => Dornica\AccessHub\Authentication\Models\UserActivation::class,
        'user_login_log' => Dornica\AccessHub\Authentication\Models\UserLoginLog::class,
        'user_failed_login_log' => Dornica\AccessHub\Authentication\Models\UserFailedLoginLog::class,

        // OTP
        'sms_provider' => Dornica\AccessHub\OTP\Models\SmsProvider::class,
    ],

    'events' => [
        'user' => [
            'language_chosen' => null,
            'language_changed' => null,
            'portal_chosen' => null,
            'portal_changed' => null,
            'logged_out' => null,
            'logged_in' => null,
            'role_chosen' => null,
            'role_changed' => null,
        ]
    ]
];
