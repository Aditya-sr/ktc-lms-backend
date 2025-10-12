<?php

use App\Http\Controllers\StandardController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\v1\AdminController;
use App\Http\Controllers\v1\SubjectController;
use App\Http\Controllers\v1\TeacherContactController;
use App\Http\Controllers\v1\TeacherController;
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
    Route::post('/teacher/login', [TeacherController::class, 'teacherLogin']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile', [AdminController::class, 'adminProfile']);
        Route::get('subjects', [SubjectController::class, 'index']);
        Route::get('/standards', [StandardController::class, 'index']);


        // Teacher apis-


        Route::prefix('teacher')->group(function () {
            Route::get('/profile', [TeacherController::class, 'teacherProfile']);

            //Admin Api
            Route::prefix('admin')->group(function () {
                Route::post('/contact', [TeacherContactController::class, 'teacherAdminContact']);
                Route::get('/contact-list', [TeacherContactController::class, 'teacherAdminContactList']);
                Route::post('/contact-reply', [TeacherContactController::class, 'teacherAdminContactReply']);
            });
        });
    });
});
