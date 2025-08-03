<?php

namespace App\Livewire\Admin;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Livewire\Component;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class Home extends Component
{
    public $searchQuery = '';
    public $searchResults = [];
    public $recentSearches = [];

    // Map route names to user-friendly labels
    protected $routeLabels = [
        'admin.home' => 'Dashboard',
        'admin.standard' => 'Class Management',
        'admin.student' => 'Student Management',
        'admin.teacher' => 'Teacher Management',
        'admin.announcement' => 'Announcements',
        'admin.timetable' => 'Class Timetable',
        'admin.arrangement' => 'Seating Arrangement',
        'admin.fee' => 'Fee Management',
        'admin.homework' => 'Homework',
        'admin.attendance' => 'Attendance',
        'admin.syllabus' => 'Syllabus',
        'admin.calender' => 'School Calendar',
        'admin.rules-and-regulation' => 'School Rules',
        'admin.content' => 'Learning Content',
        'admin.performance' => 'Performance Reports',
        'admin.analytics' => 'Analytics',
        'admin.quiz' => 'Quizzes',
        'admin.library' => 'Library',
        'admin.support' => 'Support',
        'admin.id-card' => 'ID Cards',
        'admin.admit-card' => 'Admit Cards',
        'admin.seating-plan' => 'Exam Seating Plan',
        'admin.exam-copy' => 'Exam Papers',
        'admin.report-card' => 'Report Cards',
        'admin.contact-admin' => 'Contact Admin',
        'admin.about-app' => 'About LMS',
        'admin.upgrade-standard' => 'Class Promotion',
        'admin.rate-lms' => 'Rate LMS',
        'admin.enqueries' => 'Enquiries',
        'admin.terms-and-condition' => 'Terms & Conditions',
        'admin.profile' => 'My Profile',
        'admin.notification' => 'Notifications',
    ];

    public function mount()
    {
        // Load recent searches from session
        $this->recentSearches = Session::get('admin_recent_searches', []);
    }

    public function updatedSearchQuery()
    {
        if (empty($this->searchQuery)) {
            $this->searchResults = [];
            return;
        }

        $query = strtolower($this->searchQuery);
        $this->searchResults = [];

        foreach ($this->routeLabels as $route => $label) {
            if (str_contains(strtolower($label), $query)) {
                $this->searchResults[$route] = $label;
            }
        }
    }

    public function selectResult($route)
    {
        if (isset($this->routeLabels[$route])) {
            $this->addToRecentSearches($this->routeLabels[$route]);
            return redirect()->route($route, ['organization' => FacadesAuth::user()->organization]);
        }
    }

    protected function addToRecentSearches($searchTerm)
    {
        $searches = Session::get('admin_recent_searches', []);

        // Remove if already exists to prevent duplicates
        $searches = array_filter($searches, fn($item) => $item['term'] !== $searchTerm);

        // Add new search at beginning
        array_unshift($searches, [
            'term' => $searchTerm,
            'time' => now(),
        ]);

        // Keep only last 10 searches
        $searches = array_slice($searches, 0, 10);

        Session::put('admin_recent_searches', $searches);
        $this->recentSearches = $searches;
    }

    public function clearRecentSearches()
    {
        Session::forget('admin_recent_searches');
        $this->recentSearches = [];
    }

    public function render()
    {
        return view('livewire.admin.home');
    }
}