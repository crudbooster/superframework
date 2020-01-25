<?php

return [
    'admin' => 'App\\Modules\\Admin\\Controllers\\AdminController@index',
    'admin/index' => 'App\\Modules\\Admin\\Controllers\\AdminController@index',
    'admin/login' => 'App\\Modules\\Admin\\Controllers\\AdminController@login',
    'admin/logout' => 'App\\Modules\\Admin\\Controllers\\AdminController@logout',
    'admin-api/auth' => 'App\\Modules\\AdminAPI\\Controllers\\ApiAuthController@index',
    'admin-api/auth/check-session' => 'App\\Modules\\AdminAPI\\Controllers\\ApiAuthController@checkSession',
    'admin-api/users' => 'App\\Modules\\AdminAPI\\Controllers\\ApiUsersController@index',
    'admin-api/users/update-profile' => 'App\\Modules\\AdminAPI\\Controllers\\ApiUsersController@updateProfile',
    'home' => 'App\\Modules\\Site\\Controllers\\Home@index',
    'home/index' => 'App\\Modules\\Site\\Controllers\\Home@index',
];