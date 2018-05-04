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
    $router->group(['prefix' => 'appearance'],function (Router $router)
    {
        $router->resource('/menu', 'Website\MenuController', ['except' => ['create']]);
        $router->resource('/widgets', 'Website\WidgetController');
        $router->resource('/layouts', 'Website\LayoutController');
        $router->resource('/templates', 'Website\TemplatesController');
        $router->resource('/templates/{tid}/meta', 'Website\TemplatesMetaController');
    });
    $router->resource('/pages', 'Website\PageController');
    $router->resource('/pages/{pid}/data', 'Website\PageDataController', ['except' => ['create']]);
    $router->resource('/applications/delegates', 'Website\DelegatesFormController');
    $router->resource('/applications/chapter', 'Website\NewChapterFormController');
    $router->resource('/applications/recruitment', 'Website\ReqruitmentsFormController');
    $router->resource('/contact', 'Website\ContactFormController');
});
