<?php

return [
    "app_name" => $_ENV['APP_NAME'],

    // The default is false, please be careful if you set the display errors to TRUE, the error will show up public.
    "display_errors"=> $_ENV['DISPLAY_ERRORS'],

    // Logging error can decrease web speed. Default is true.
    // The default apache or web server is there is a log also.
    'logging_errors'=> $_ENV['LOGGING_ERRORS'],

    // hash salt key used to make a password combination
    "hash_salt_key" => "super88",

    // Application language
    "lang"=> "en",
    "fallback_lang"=> "en",

    // CSRF Token
    "csrf_token"=> true,
    "csrf_token_ignore"=> ["api/*"],

    // Session Configuration
    "session_lifetime"=> 130,
    "session_secure"=> false,
    "session_httpOnly"=> true
];