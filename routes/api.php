<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssetController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'Login']);
Route::post('/register', [AuthController::class, 'Register']);



Route::group(['middleware'=> ['auth:sanctum']], function () {
    
    Route::post('/register/tag', [AssetController::class, 'registerTag']);
    Route::post('/mda', [AssetController::class, 'createMda']);
    Route::post('/location', [AssetController::class, 'createLocation']);
    Route::post('/custodian', [AssetController::class, 'createCustodian']);
    Route::post('/mda/list ', [AssetController::class, 'mdaList']);
    Route::post('/asset/create', [AssetController::class, 'store']);
    Route::get('/assets/mylist', [AssetController::class, 'myList']);
    
    

    
    
    
    
        

    Route::post('/logout', [AuthController::class, 'Logout']);
});