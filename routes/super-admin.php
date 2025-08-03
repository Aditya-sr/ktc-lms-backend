<?php

use App\Livewire\SuperAdmin\Analytics;
use App\Livewire\SuperAdmin\Dashboard;
use App\Livewire\SuperAdmin\Enquiry;
use App\Livewire\SuperAdmin\Fees;
use App\Livewire\SuperAdmin\Login;
use App\Livewire\SuperAdmin\PortalWebsite;
use App\Livewire\SuperAdmin\Rating;
use App\Livewire\SuperAdmin\Schools;
use App\Livewire\SuperAdmin\Student;
use App\Livewire\SuperAdmin\Support;
use App\Livewire\SuperAdmin\Teacher;
use App\Livewire\SuperAdmin\TermsCondition;
use App\Livewire\SuperAdmin\WebsiteData;
use Illuminate\Support\Facades\Route;

// Super Admin Routes
Route::middleware(['guest:web'])->group(function () {
    Route::get('admin', Login::class)->name('super-admin.login');
});

Route::middleware(['auth:web', 'super-admin'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('super-admin.dashboard');
    Route::get('schools', Schools::class)->name('super-admin.schools');
    Route::get('students', Student::class)->name('super-admin.students');
    Route::get('teachers', Teacher::class)->name('super-admin.teachers');
    Route::get('fees', Fees::class)->name('super-admin.fees');
    Route::get('enquiries', Enquiry::class)->name('super-admin.enquiries');
    Route::get('support', Support::class)->name('super-admin.support');
    Route::get('website-data', WebsiteData::class)->name('super-admin.website-data');
    Route::get('analytics', Analytics::class)->name('super-admin.analytics');
    Route::get('portal-website', PortalWebsite::class)->name('super-admin.portal-website');
    Route::get('terms-condition', TermsCondition::class)->name('super-admin.terms-condition');
    Route::get('rating', Rating::class)->name('super-admin.rating');
});
