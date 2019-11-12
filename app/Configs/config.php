<?php

return [
    // Set the base url, end with slash (/). E.g: http://localhost/super/
    "base_url"=> "http://localhost/super/",

    // Set the application name
    "app_name"=> "Super Framework",

    // Show measure php consume time
    "consume_time_process"=> true,

    // Set default controller
    "default_controller"=> \App\Modules\Site\Controllers\Home::class,

    /*
     * You can disable session by set to false.
     * For example you want to use API only, so you can disable session feature to best performance
     */
    "session_enable"=> false,

    // Ignore CSRF security for url prefix pattern
    "csrf_exception"=> ["api"]
];