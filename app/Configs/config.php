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

    // Ignore CSRF security for url prefix pattern
    "csrf_exception"=> ["api"]
];