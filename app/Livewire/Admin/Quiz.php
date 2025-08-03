<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Quiz extends Component
{
    public $activeTab = 'mcq_questions';
    public $subTab = 'mcq_questions';

    public $tabs = [
        'mcq_questions' => [
            'mcq_questions' => 'Mcq Question',
        ],
        'student_answers' => [
            'answers' => 'Mcq Answer',
        ]
    ];

    public function showTab($tab)
    {
        $this->activeTab = $tab;
        $this->subTab = array_key_first($this->tabs[$tab]);
    }

    public function setSubTab($subTab)
    {
        $this->subTab = $subTab;
    }

    public function render()
    {
        return view('livewire.admin.quiz');
    }
}
