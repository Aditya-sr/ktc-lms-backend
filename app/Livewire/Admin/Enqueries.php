<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Enqueries extends Component
{
    public $activeTab = 'teacher';

    public function showTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.enqueries');
    }
}
