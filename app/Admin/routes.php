<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::get('', function () {
    return redirect('/dashboard');
});


Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/dashboard', 'HomeController@index');

});
