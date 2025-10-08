<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Guard
    |--------------------------------------------------------------------------
    */

    'default_guard' => config('dornica-app.default_guard'),
//    'default_guard' =>'admin',

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    */

    'routes' => [
        'dashboard' => 'admin.dashboard.index',
//        'dashboard' => 'user.dashboard.index',
    ],

    /*
    |--------------------------------------------------------------------------
    | Table Generator
    |--------------------------------------------------------------------------
    */

    'table_generator' => [
        "default_driver" => Dornica\PanelKit\Generator\Table\Enums\Driver::MODEL,

        //        "default_body_centered" => false,

        "pagination_page_sizes" => [10, 20, 50, 100],

        //"view" => "table-generator::default",

        'api_connections' => [
            /*'mmt' => [
                'driver' => Dornica\PanelKit\Generator\Table\Enums\API\Driver::DORAPI,
                'base_url' => 'https://api.dornica.net/v1',
                'headers' => [
                    'X-Auth-Key' => '4478-9225-291856a51130-59277adf-8a27'
                ]
            ]*/
        ]
    ],

//    /*
//    |--------------------------------------------------------------------------
//    | Theme
//    |--------------------------------------------------------------------------
//    */
//
//    'current_theme' => 'default',
//
//    'theme' => [
//        'assets' => [
//            'favicon' => 'vendor/dornica/panel-kit/images/default/favicon.ico',
//
//            'default_logo' => 'vendor/dornica/panel-kit/images/default/default.png',
//
//            'css' => [
//                // project customizations
//                //
//            ],
//
//            'js' => [
//                // project customizations
//                //
//            ],
//
//            'pre_scripts' => [
//
//            ],
//
//            'additional_blades' => [
//                // Add blades here
//                // Example: 'components.custom-footer',
//            ],
//        ],
//    ],
//
//    /*
//    |--------------------------------------------------------------------------
//    | Components
//    |--------------------------------------------------------------------------
//    */
//
//    'components' => [
//        'number_input' => [
//            'text_align' => 'right'
//        ],
//        'date_pickers' => [
//            'text_align' => 'right',
//            'date_format' => null,
//            'datetime_format' => null,
//            'time_format' => null,
//            'allow_typing' => false,
//            'disabled_days' => null,
//            'auto_close' => true,
//            'allow_same_day_selection' => true
//        ],
//        'select' => [
//            'multi_allow_select_all' => null,
//            'multi_display_selected_as_count' => null,
//        ],
//        'data_item' => [
//            'default_value' => null,
//        ]
//    ],
//];





/*
|--------------------------------------------------------------------------
| Theme
|--------------------------------------------------------------------------
*/

'current_theme' => 'default',

    'theme' => [
    'assets' => [
        'favicon' => 'assets/image/favicon.png',

        'default_logo' => 'assets/image/logo.png',

        'css' => [
            // project customizations
            'assets/theme/css/variables.css',
            'assets/custom/css/style.css'
        ],

        'js' => [
            // project customizations
            'assets/custom/js/script.js'
        ],


        'pre_scripts' => [

        ],
    ],
],

    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    */

    'components' => [
    'number_input' => [
        'text_align' => 'right'
    ],
    'date_pickers' => [
        'text_align' => 'right',
        'date_format' => null,
        'datetime_format' => null,
        'time_format' => null,
        'allow_typing' => true,
        'disabled_days' => null,
        'auto_close' => true,
        'allow_same_day_selection' => false
    ],
    'select' => [
        'multi_allow_select_all' => true,
        'multi_display_selected_as_count' => true,
    ],
    'data_item' => [
        'default_value' => '-',
    ]
],
];





