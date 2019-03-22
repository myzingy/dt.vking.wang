<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {


    Route::get('/device/brands', 'DeviceController@brands');
    Route::get('/device/brandsDetail', 'DeviceController@brandsDetail');

    //$router->get('/', 'HomeController@index');
    $router->get('/',function (){
        return Redirect::to("/admin/project");
    });
    $router->resource('/project', ProjectController::CLASS);
    $router->resource('/projectElevator', ProjectElevatorController::CLASS);
    $router->resource('/elevator', ElevatorController::CLASS);
    $router->resource('/device', DeviceController::CLASS);
    $router->resource('/deviceFunc', DeviceFuncController::CLASS);
    $router->resource('/deviceFreight', DeviceFreightController::CLASS);
    $router->resource('/deviceFitment', DeviceFitmentController::CLASS);

    Route::get('/project/{pid}/elevator', 'ProjectController@elevator');
    Route::get('/project/{pid}/elevator/{eid}', 'ProjectController@elevatorBind');

    Route::get('/elevator/{eid}/funfit', 'ElevatorController@funfit');
});
