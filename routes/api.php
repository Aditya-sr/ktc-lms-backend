<?php

use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//v1 api 
require __DIR__ . '/v1.php';

//Test Api 
Route::post('/test', [TestController::class, 'index']);
