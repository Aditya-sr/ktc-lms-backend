<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class QuickLinks extends Component
{
    public $links = [];

    public function mount()
    {
        $this->links = [
            ['title' => 'Home', 'route' => 'admin.home', 'color' => 'blue', 'icon' => 'home'],
            ['title' => 'Standard', 'route' => 'admin.standard', 'color' => 'indigo', 'icon' => 'book-open'],
            ['title' => 'Add Exam', 'route' => 'admin.add-exam', 'color' => 'purple', 'icon' => 'paper-clip'],
            ['title' => 'Student', 'route' => 'admin.student', 'color' => 'green', 'icon' => 'users'],
            ['title' => 'Teacher', 'route' => 'admin.teacher', 'color' => 'yellow', 'icon' => 'user'],
            ['title' => 'Announcement', 'route' => 'admin.announcement', 'color' => 'pink', 'icon' => 'megaphone'],
            ['title' => 'Time Table', 'route' => 'admin.timetable', 'color' => 'purple', 'icon' => 'calendar-days'],
            ['title' => 'Arrangement', 'route' => 'admin.arrangement', 'color' => 'blue', 'icon' => 'table-cells'],
            ['title' => 'Fee', 'route' => 'admin.fee', 'color' => 'green', 'icon' => 'credit-card'],
            ['title' => 'Homework', 'route' => 'admin.homework', 'color' => 'orange', 'icon' => 'pencil-square'],
            ['title' => 'Attendance', 'route' => 'admin.attendance', 'color' => 'teal', 'icon' => 'check-badge'],
            ['title' => 'Syllabus', 'route' => 'admin.syllabus', 'color' => 'rose', 'icon' => 'book-open'],
            ['title' => 'Calender', 'route' => 'admin.calender', 'color' => 'cyan', 'icon' => 'calendar'],
            ['title' => 'Rules & Regulation', 'route' => 'admin.rules-and-regulation', 'color' => 'lime', 'icon' => 'scale'],
            ['title' => 'Content', 'route' => 'admin.content', 'color' => 'fuchsia', 'icon' => 'document-text'],
            ['title' => 'Performance', 'route' => 'admin.performance', 'color' => 'red', 'icon' => 'chart-bar'],
            ['title' => 'Analytics', 'route' => 'admin.analytics', 'color' => 'blue', 'icon' => 'chart-pie'],
            ['title' => 'Quiz', 'route' => 'admin.quiz', 'color' => 'purple', 'icon' => 'question-mark-circle'],
            ['title' => 'Library', 'route' => 'admin.library', 'color' => 'green', 'icon' => 'building-library'],
            // ['title' => 'Support', 'route' => 'admin.support', 'color' => 'yellow', 'icon' => 'lifebuoy'],
            ['title' => 'ID Card', 'route' => 'admin.id-card', 'color' => 'pink', 'icon' => 'identification'],
            ['title' => 'Admit Card', 'route' => 'admin.admit-card', 'color' => 'rose', 'icon' => 'document-duplicate'],
            ['title' => 'Seating Plan', 'route' => 'admin.seating-plan', 'color' => 'sky', 'icon' => 'table-cells'],
            ['title' => 'Exam Copy', 'route' => 'admin.exam-copy', 'color' => 'violet', 'icon' => 'clipboard-document-check'],
            ['title' => 'Report Card', 'route' => 'admin.report-card', 'color' => 'amber', 'icon' => 'clipboard-document-list'],
            ['title' => 'Contact Admin', 'route' => 'admin.contact-admin', 'color' => 'gray', 'icon' => 'phone'],
            ['title' => 'About App', 'route' => 'admin.about-app', 'color' => 'orange', 'icon' => 'information-circle'],
            ['title' => 'Upgrade Standard', 'route' => 'admin.upgrade-standard', 'color' => 'green', 'icon' => 'arrow-trending-up'],
            ['title' => 'Rate LMS', 'route' => 'admin.rate-lms', 'color' => 'pink', 'icon' => 'star'],
            ['title' => 'Enquiries', 'route' => 'admin.enqueries', 'color' => 'purple', 'icon' => 'inbox'],
            ['title' => 'Terms & Conditions', 'route' => 'admin.terms-and-condition', 'color' => 'yellow', 'icon' => 'document-check'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.quick-links');
    }
}
