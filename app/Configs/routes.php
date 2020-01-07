<?php

return [
    'admin' => 'App\\Modules\\Admin\\Controllers\\AdminController@index',
    'admin/index' => 'App\\Modules\\Admin\\Controllers\\AdminController@index',
    'admin/login' => 'App\\Modules\\Admin\\Controllers\\AdminController@login',
    'admin-api/auth' => 'App\\Modules\\AdminAPI\\Controllers\\ApiAuthController@index',
    'admin-api/auth/login' => 'App\\Modules\\AdminAPI\\Controllers\\ApiAuthController@login',
    'admin-api/auth/check-session' => 'App\\Modules\\AdminAPI\\Controllers\\ApiAuthController@checkSession',
    'admin-api/auth/logout' => 'App\\Modules\\AdminAPI\\Controllers\\ApiAuthController@logout',
    'admin-api/users' => 'App\\Modules\\AdminAPI\\Controllers\\ApiUsersController@index',
    'admin-api/users/update-profile' => 'App\\Modules\\AdminAPI\\Controllers\\ApiUsersController@updateProfile',
    'home' => 'App\\Modules\\Site\\Controllers\\Home@index',
    'home/index' => 'App\\Modules\\Site\\Controllers\\Home@index',
];