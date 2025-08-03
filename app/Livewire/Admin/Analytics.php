<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\StudentDetail;
use App\Models\StudentIdCard;

class Analytics extends Component
{
    public $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
    public $studentGrowthData = [];
    public $idCardStatusData = [];
    public $classDistributionData = [];

    public function mount()
    {
        // Generate or fetch your actual data here
        $this->studentGrowthData = $this->getStudentGrowthData();
        $this->idCardStatusData = $this->getIdCardStatusData();
        $this->classDistributionData = $this->getClassDistributionData();
    }

    protected function getStudentGrowthData()
    {
        // Replace with actual database query
        return [
            'new_students' => [45, 60, 75, 90, 50, 70, 95],
            'total_students' => [200, 400, 600, 800, 1000, 1200, 1400]
        ];
    }

    protected function getIdCardStatusData()
    {
        // Replace with actual database query
        return [
            'active' => 1024,
            'expired' => 87,
            'inactive' => 56,
            'pending' => 23
        ];
    }

    protected function getClassDistributionData()
    {
        // Replace with actual database query
        return [120, 110, 105, 100, 98, 115, 125, 130, 145, 150];
    }

    public function render()
    {
        return view('livewire.admin.analytics', [
            'monthsJson' => json_encode($this->months),
            'studentGrowthJson' => json_encode($this->studentGrowthData),
            'idCardStatusJson' => json_encode($this->idCardStatusData),
            'classDistributionJson' => json_encode($this->classDistributionData)
        ]);
    }
}