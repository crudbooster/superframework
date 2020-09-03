<?php

return [
    "app_name" => "PHP Super Framework",
    // Set default controller
    "default_controller"=> \App\Main\Controllers\HomeController::class,
    "default_method"=> "index",

    // The default is false, please be careful if you set the display errors to TRUE
    "display_errors"=> false,

    // hash salt key used to make a password combination
    "hash_salt_key" => "super88",

    // Application language
    "lang"=> "en",
    "fallback_lang"=> "en"
];