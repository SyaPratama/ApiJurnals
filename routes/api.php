<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\JurnalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'],function(){
    Route::controller(AuthenticationController::class)->group(function(){
        Route::post('secret/admin/register','Register');
        Route::post('auth/login','Login');
        Route::post('secret/admin/users','getAllUser');
    });

    Route::middleware('auth:sanctum')->group(function(){
        Route::post('auth/logout',[AuthenticationController::class,'Logout']);
        Route::get('users',[AuthenticationController::class,'getUserLogged']);
        Route::controller(JurnalController::class)->group(function(){
            Route::post('jurnals','createJurnals');
            Route::get('jurnals','getAllJurnals');
            Route::get('jurnals/{id}','getJurnalsById');
        });
    });
});
