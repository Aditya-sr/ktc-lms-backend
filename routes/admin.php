<?php

use App\Livewire\Admin\Home;
use App\Livewire\Admin\Standard;
use App\Livewire\Admin\Student;
use App\Livewire\Admin\Teacher;
use App\Livewire\Admin\Announcement;
use App\Livewire\Admin\TimeTable;
use App\Livewire\Admin\Arrangement;
use App\Livewire\Admin\Fee;
use App\Livewire\Admin\Homework;
use App\Livewire\Admin\Attendance;
use App\Livewire\Admin\Syllabus;
use App\Livewire\Admin\Calender;
use App\Livewire\Admin\RulesAndRegulation;
use App\Livewire\Admin\Content;
use App\Livewire\Admin\Performance;
use App\Livewire\Admin\Analytics;
use App\Livewire\Admin\Quiz;
use App\Livewire\Admin\Library;
use App\Livewire\Admin\Support;
use App\Livewire\Admin\IdCard;
use App\Livewire\Admin\AdmitCard;
use App\Livewire\Admin\SeatingPlan;
use App\Livewire\Admin\ExamCopy;
use App\Livewire\Admin\ReportCard;
use App\Livewire\Admin\ContactAdmin;
use App\Livewire\Admin\AboutApp;
use App\Livewire\Admin\AddExam;
use App\Livewire\Admin\UpgradeStandard;
use App\Livewire\Admin\RateLms;
use App\Livewire\Admin\Enqueries;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\QuickLinks;
use App\Livewire\Admin\TermsAndCondition;
use App\Livewire\Components\Notification;
use App\Livewire\Components\Profile;
use App\Livewire\ResetPassword;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest:web'])->group(function () {
    Route::get('login', Login::class)->name('admin.login');
    Route::get('reset-password', ResetPassword::class)->name('reset.password');
});

Route::middleware(['auth:web', 'admin'])->group(function () {
    Route::prefix('/{organization}')->group(function () {
        Route::get('/home', Home::class)->name('admin.home');
        Route::get('/quick-links', QuickLinks::class)->name('admin.quick-links');
        Route::get('/standard', Standard::class)->name('admin.standard');
        Route::get('/add-exam', AddExam::class)->name('admin.add-exam');
        Route::get('/student', Student::class)->name('admin.student');
        Route::get('/teacher', Teacher::class)->name('admin.teacher');
        Route::get('/announcement', Announcement::class)->name('admin.announcement');
        Route::get('/timetable', TimeTable::class)->name('admin.timetable');
        Route::get('/arrangement', Arrangement::class)->name('admin.arrangement');
        Route::get('/fee', Fee::class)->name('admin.fee');
        Route::get('/homework', Homework::class)->name('admin.homework');
        Route::get('/attendance', Attendance::class)->name('admin.attendance');
        Route::get('/syllabus', Syllabus::class)->name('admin.syllabus');
        Route::get('/calender', Calender::class)->name('admin.calender');
        Route::get('/rules-and-regulation', RulesAndRegulation::class)->name('admin.rules-and-regulation');
        Route::get('/content', Content::class)->name('admin.content');
        Route::get('/performance', Performance::class)->name('admin.performance');
        Route::get('/analytics', Analytics::class)->name('admin.analytics');
        Route::get('/quiz', Quiz::class)->name('admin.quiz');
        Route::get('/library', Library::class)->name('admin.library');
        Route::get('/support', Support::class)->name('admin.support');
        Route::get('/id-card', IdCard::class)->name('admin.id-card');
        Route::get('/admit-card', AdmitCard::class)->name('admin.admit-card');
        Route::get('/seating-plan', SeatingPlan::class)->name('admin.seating-plan');
        Route::get('/exam-copy', ExamCopy::class)->name('admin.exam-copy');
        Route::get('/report-card', ReportCard::class)->name('admin.report-card');
        Route::get('/contact-admin', ContactAdmin::class)->name('admin.contact-admin');
        Route::get('/about-app', AboutApp::class)->name('admin.about-app');
        Route::get('/upgrade-standard', UpgradeStandard::class)->name('admin.upgrade-standard');
        Route::get('/rate-lms', RateLms::class)->name('admin.rate-lms');
        Route::get('/enqueries', Enqueries::class)->name('admin.enqueries');
        Route::get('/terms-and-condition', TermsAndCondition::class)->name('admin.terms-and-condition');

        //Navbar Route
        Route::get('/profile', Profile::class)->name('admin.profile');
        Route::get('/notification', Notification::class)->name('admin.notification');
    });
});
