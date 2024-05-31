<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\JurnalController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\GoogleSpreedSheetController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'],function(){

    Route::controller(AuthenticationController::class)->group(function(){
        Route::post('secret/admin/register','Register');
        Route::post('auth/login','Login');
        Route::post('secret/admin/users','getAllUser');
        Route::post('secret/admin/kelas',[KelasController::class,'AddKelas']);
    });

    Route::middleware('auth:sanctum')->group(function(){
        Route::post('auth/logout',[AuthenticationController::class,'Logout']);
        Route::get('users',[AuthenticationController::class,'getUserLogged']);

        Route::controller(KelasController::class)->group(function(){
            Route::post('kelas','addKelas');
            Route::get('kelas','getKelas');
            Route::get('kelas/users','getKelasByUser');
        });

        Route::controller(JurnalController::class)->group(function(){
            Route::post('jurnals','createJurnals');
            Route::get('jurnals','getAllJurnals');
            Route::get('jurnals/users','getJurnalUser');
            Route::get('jurnals/{id}','getJurnalsById');
        });
    });
});
