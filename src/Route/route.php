<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::group(
    [
        'prefix'     =>config('Amer.Drivers.route_prefix','Drivers'),
        'namespace'  =>config('Amer.Drivers.Controllers'),
        'middleware' =>array_merge((array) config('Amer.Amer.web_middleware'),(array) config('Amer.Security.auth.middleware_key')),
        'name'=>config('Amer.Drivers.routeName_prefix','Drivers.'),
    ],
    function(){
        Route::Amer('office_cars','office_carsAmerController');
        Route::Amer('office_degrees','office_degreesAmerController');
        Route::Amer('office_chairmen','office_chairmenAmerController');
        Route::Amer('office_drivers','office_driversAmerController');
        Route::Amer('office_employers','office_employersAmerController');
        Route::Amer('offics_chairmenmamorias','offics_chairmenmamoriasAmerController');
        Route::Amer('offics_driversmamorias','offics_driversmamoriasAmerController');
        Route::Amer('offics_employersmamorias','offics_employersmamoriasAmerController');
    });
