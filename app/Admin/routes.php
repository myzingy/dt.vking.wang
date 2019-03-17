<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    //$router->get('/', 'HomeController@index');
    $router->get('/',function (){
        return Redirect::to("/admin/object");
    });
    $router->resource('/project', ProjectController::CLASS);
    $router->resource('/device', DeviceController::CLASS);
    $router->resource('/deviceFunc', DeviceFuncController::CLASS);
    $router->resource('/deviceFreight', DeviceFreightController::CLASS);
    $router->resource('/deviceFitment', DeviceFitmentController::CLASS);
});
