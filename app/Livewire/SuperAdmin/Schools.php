<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Organization;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use WireUi\Traits\WireUiActions;

class Schools extends Component
{
    use WireUiActions;
    public $schoolName;
    public $email;
    public $mobileNumber;
    public $state;
    public $educationBoard;
    public $schoolCode;
    public $serialNumber;
    public $showModal = false;
    public $editId = null;
    protected $listeners = [
        'editSchool' => 'onEdit',
        'deleteSchool' => 'onDelete'
    ];

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function onEdit($id)
    {
        $this->editId = $id;
        $school = Organization::find($id);

        $this->schoolName = $school->name;
        $this->email = $school->email;
        $this->mobileNumber = $school->mobile_number;
        $this->state = $school->state;
        $this->educationBoard = $school->education_board;
        $this->schoolCode = $school->school_code;
        $this->serialNumber = $school->serial_number;

        $this->showModal = true;
    }

    public function saveSchool()
    {
        $this->validate([
            'schoolName' => 'required|string',
            'email' => 'required|email|unique:organizations,email,' . $this->editId,
            'mobileNumber' => 'required|string',
            'state' => 'required|string',
            'educationBoard' => 'required|string',
            'schoolCode' => 'required|string|unique:organizations,school_code,' . $this->editId,
            'serialNumber' => 'required|string|unique:organizations,serial_number,' . $this->editId,
        ]);

        DB::transaction(function () {
            $orgData = [
                'name' => $this->schoolName,
                'email' => $this->email,
                'mobile_number' => $this->mobileNumber,
                'state' => $this->state,
                'education_board' => $this->educationBoard,
                'school_code' => $this->schoolCode,
                'serial_number' => $this->serialNumber,
                'status' => true
            ];

            if ($this->editId) {
                $organization = Organization::findOrFail($this->editId);
                $organization->update($orgData);

                User::where('organization_id', $this->editId)
                    ->where('role', 'admin')
                    ->first()
                    ->update([
                        'email' => $this->email,
                        'mobile_number' => $this->mobileNumber,
                    ]);
            } else {
                $organization = Organization::create($orgData);

                $password = "123456789";
                User::create([
                    'name' => $this->schoolName . ' Admin',
                    'email' => $this->email,
                    'mobile_number' => $this->mobileNumber,
                    'organization_id' => $organization->id,
                    'role' => 'admin',
                    'password' => Hash::make($password),
                ]);
            }
        });

        $this->closeModal();
        $this->dispatch('refreshSchools');
        $this->notification()->success('School created successfully!');
    }

    // Delete school
    public function onDelete($id)
    {
        $school = Organization::find($id);
        $school->delete();

        // Delete associated admin user
        User::where('organization_id', $id)
            ->where('role', 'admin')
            ->delete();

        $this->dispatch('refreshSchools');
    }

    private function resetForm()
    {
        $this->reset([
            'schoolName',
            'email',
            'mobileNumber',
            'state',
            'educationBoard',
            'schoolCode',
            'serialNumber',
            'editId'
        ]);
    }

    public function render()
    {
        return view('livewire.super-admin.schools');
    }
}
