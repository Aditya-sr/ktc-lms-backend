<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Performance extends Component
{
    public $activeTab = 'list';

    public function showTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.performance');
    }
}
