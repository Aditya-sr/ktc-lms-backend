<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\v1\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//v1 api 
require __DIR__ . '/v1.php';

//Test Api 
Route::post('/test', [TestController::class, 'index']);


Route::prefix('v1/admin')->group(function () {
    Route::post('login', [AdminController::class, 'adminLogin']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile', [AdminController::class, 'adminProfile']);
    });
});
