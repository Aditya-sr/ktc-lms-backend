<?php

use App\Http\Controllers\SendNotificationController;
use App\Http\Controllers\v1\AnnouncementController;
use App\Http\Controllers\v1\AttendanceController;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\ContentController;
use App\Http\Controllers\v1\HomeWorkController;
use App\Http\Controllers\v1\LibraryController;
use App\Http\Controllers\v1\McqController;
use App\Http\Controllers\v1\StudentContactController;
use App\Http\Controllers\v1\SubjectController;
use App\Http\Controllers\v1\TeacherContactController;
use App\Http\Controllers\v1\TeacherController;
use App\Http\Controllers\v1\UserController;
use Illuminate\Support\Facades\Route;

//Unauthentication Route
Route::get('/unauthenticate', [AuthController::class, 'unauthenticate'])->name('unauthenticate');

//v1 version
Route::prefix('v1')->group(function () {
    Route::post('/user/login', [UserController::class, 'studentLogin']);
    Route::post('/teacher/login', [TeacherController::class, 'teacherLogin']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('v1')->group(function () {

        //Save Fcm Token
        Route::post('/save-fcm-token', [SendNotificationController::class, 'saveFcmToken']);

        //Auth Api
        Route::post('/update-password', [AuthController::class, 'updatePassword']);
        Route::get('/school-info', [AuthController::class, 'schoolInfo']);

        // User routes here
        Route::prefix('user')->group(function () {
            Route::get('/profile', [UserController::class, 'studentProfile']);

            //Admin Api
            Route::prefix('admin')->group(function () {
                Route::post('/contact', [StudentContactController::class, 'studentAdminContact']);
                Route::get('/contact-list', [StudentContactController::class, 'studentAdminContactList']);
                Route::post('/contact-reply', [StudentContactController::class, 'studentAdminContactReply']);
            });
        });

        // Teacher routes here
        Route::prefix('teacher')->group(function () {
            Route::get('/profile', [TeacherController::class, 'teacherProfile']);

            //Admin Api
            Route::prefix('admin')->group(function () {
                Route::post('/contact', [TeacherContactController::class, 'teacherAdminContact']);
                Route::get('/contact-list', [TeacherContactController::class, 'teacherAdminContactList']);
                Route::post('/contact-reply', [TeacherContactController::class, 'teacherAdminContactReply']);
            });
        });

        //Announcement Routes All
        Route::prefix('announcement')->group(function () {
            Route::post('/', [AnnouncementController::class, 'announcementList']);
            Route::get('/{id}', [AnnouncementController::class, 'getAnnouncement']);
        });

        //Library Routes All
        Route::prefix('library')->group(function () {
            Route::post('/', [LibraryController::class, 'libraryList']);
            Route::get('/{id}', [LibraryController::class, 'getLibraryItem']);
        });

        //Subject Routes All
        Route::prefix('subject')->group(function () {
            Route::get('/', [SubjectController::class, 'getAllSubject']);
        });

        //Content Routes All
        Route::prefix('content')->group(function () {
            Route::post('/upload', [ContentController::class, 'saveChapterTopic']);
            Route::post('/get', [ContentController::class, 'getChapterTopics']);
            Route::post('/chapter/{chapter_id}', [ContentController::class, 'updateChapterName']);
            Route::delete('/chapter-delete/{chapter_id}', [ContentController::class, 'deleteChapter']);
            Route::post('/topic/{topic_id}', [ContentController::class, 'updateTopic']);
            Route::delete('/topic-delete/{topic_id}', [ContentController::class, 'deleteTopic']);
        });

        //Home Work Routes All
        Route::prefix('homework')->group(function () {
            Route::post('/upload', [HomeWorkController::class, 'uploadHomeWork']);
            Route::post('/get', [HomeWorkController::class, 'allHomeWork']);
            Route::post('/update/{homework_id}', [HomeWorkController::class, 'updateHomeWork']);
            Route::delete('/delete/{homework_id}', [HomeWorkController::class, 'destroyHomeWork']);
            Route::get('/get/{homework_id}', [HomeWorkController::class, 'showSingleHomeWork']);
        });

        //Quiz Routes All
        Route::prefix('quiz')->group(function () {
            Route::post('/upload', [McqController::class, 'uploadQuiz']);
            Route::post('/get', [McqController::class, 'viewAllQuizzes']);
            Route::post('/submit-answer', [McqController::class, 'submitAnswer']);
            Route::post('/get/user-answer', [McqController::class, 'getUserAnswers']);
        });

        // Attendance All Routes
        Route::prefix('attendance')->group(function () {
            Route::post('/', [AttendanceController::class, 'bulkSubmitAttendance']);
            Route::post('/get-student-for-attendance', [AttendanceController::class, 'getStudentsForAttendance']);
            Route::post('/summary', [AttendanceController::class, 'getAttendanceSummary']);
        });
    });

    Route::post('/notifications/send-to-me', [SendNotificationController::class, 'sendToMe']);
});
