<?php

use Illuminate\Routing\Router;


Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => 'App\Admin\Controllers',
    'middleware' => config('admin.route.middleware'),
], function ($router) {

    /* @var \Illuminate\Routing\Router $router */
    $router->group([], function ($router) {

        /* @var \Illuminate\Routing\Router $router */
        $router->resource('auth/users', 'UserController');
        $router->resource('auth/roles', 'RoleController');
        $router->resource('auth/permissions', 'PermissionController');
        $router->resource('auth/menu', 'MenuController', ['except' => ['create']]);
        $router->resource('auth/logs', 'LogController', ['only' => ['index', 'destroy']]);
    });

    $router->get('auth/login', 'AuthController@getLogin');
    $router->post('auth/login', 'AuthController@postLogin');
    $router->get('auth/logout', 'AuthController@getLogout');
    $router->get('auth/setting', 'AuthController@getSetting');
    $router->put('auth/setting', 'AuthController@putSetting');
});


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
        $router->resource('/menu', 'Website\MenuHolderController',['as'=>'sitemenu']);
        $router->resource('/menu/{mid}/data', 'Website\MenuController',['as'=>'sitemenu']);
        $router->resource('/widgets', 'Website\WidgetController');
        $router->resource('/layouts', 'Website\LayoutController');
        $router->resource('/templates', 'Website\TemplatesController');
        $router->resource('/templates/{tid}/meta', 'Website\TemplatesMetaController');
        $router->resource('/footer', 'Website\FooterController');
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

    $router->group(['prefix' => 'posts'],function (Router $router)
    {
        $router->resource('/articles', 'Website\BlogPostController',['as'=>'post']);
        $router->resource('/category', 'Website\BlogCategoryController',['as'=>'post']);
    });


    $router->resource('/slider', 'Website\SliderController',['name'=>'slider']);
    $router->resource('/slider/{slide}/meta', 'Website\SiteSliderMetaController',['as'=>'slider']);

    $router->resource('/schedules', 'Website\ScheduleController',['name'=>'schedule']);
    $router->resource('/schedules/{schedule}/meta', 'Website\ScheduleMetaController',['as'=>'schedule']);

    $router->resource('/settings/general', 'Website\GeneralSettingsController',['as'=>'settings','only'=>['index','update']]);
    $router->resource('/timeline', 'Website\TimelineController');
    $router->resource('/forms', 'Website\FormController');
    $router->resource('/forms/{fid}/submissions','Website\FormSubmissions');




});