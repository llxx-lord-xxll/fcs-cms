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
        $router->get('/templates/{tid}/meta/{metum}/clone', 'Website\TemplatesMetaController@clone_branch')->name('meta.clone');
    });
    $router->resource('/pages', 'Website\PageController');
    $router->resource('/pages/{pid}/data', 'Website\PageDataController', ['except' => ['create']]);
    $router->resource('/applications/delegates', 'Website\DelegatesFormController',['except' => ['create']]);
    $router->resource('/applications/chapter', 'Website\NewChapterFormController',['except' => ['create']]);
    $router->resource('/applications/recruitment', 'Website\ReqruitmentsFormController',['except' => ['create']]);
    $router->resource('/contact', 'Website\ContactFormController', ['except' => ['create']]);

    $router->group(['prefix' => 'packs'],function (Router $router)
    {
        $router->get('/',function (){
            return redirect()->route('item.index');
        });
        $router->get('/create',function (){
            return redirect()->route('item.create');
        });

        $router->resource('/item', 'Website\PackageController',['wildcard' => 'id']);
        $router->resource('/groups', 'Website\PackageGroupController');

    });

    $router->group(['prefix' => 'gallery'],function (Router $router)
    {
        $router->resource('/view', 'Website\GalleryController',['except' => ['create']]);
        $router->resource('/albums', 'Website\GalleryAlbumController',['except' => ['create']]);
        $router->resource('/photos', 'Website\GalleryPhotoController',['except' => ['create']]);
    });

    $router->group(['prefix' => 'teams'],function (Router $router)
    {
        $router->resource('/view', 'Website\TeamController',['except' => ['create'],'as'=>'team']);
        $router->resource('/people', 'Website\TeamPeopleController',['as'=>'team']);
    });

    
});
