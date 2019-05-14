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
    //Route::post('/elevatorSuper/{eid}', 'ElevatorSuperController@setPrice');//审核动作
    //$router->get('/', 'HomeController@index');
    $router->get('/',function (){
        return Redirect::to("/admin/project");
    });
    $router->resource('/project', ProjectController::CLASS);
    $router->resource('/projectElevator', ProjectElevatorController::CLASS);

    $router->resource('/elevator', ElevatorController::CLASS);
    $router->resource('/elevatorSuper', ElevatorSuperController::CLASS);
    $router->resource('/elevatorFunc', ElevatorFuncController::CLASS);
    $router->resource('/elevatorFitment', ElevatorFitmentController::CLASS);

    $router->resource('/device', DeviceController::CLASS);
    $router->resource('/funtion', FuntionController::CLASS);
    $router->resource('/freight', FreightController::CLASS);
    $router->resource('/fitment', FitmentController::CLASS);
    $router->resource('/deviceYearly', DeviceYearlyController::CLASS);

    Route::get('/project/{pid}/elevator', 'ProjectController@elevator');
    Route::get('/project/{pid}/elevator/{eid}', 'ProjectController@elevatorBind');

    Route::get('/elevator/{eid}/funfit', 'ElevatorController@funfit');//配置功能装修
    Route::get('/elevator/{eid}/funfit/{fid}', 'ElevatorController@fitout');//绑定功能装修动作


});
