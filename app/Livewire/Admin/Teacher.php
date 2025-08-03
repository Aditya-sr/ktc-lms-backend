<?php

namespace App\Livewire\Admin;

use App\Helpers\CityGetHelper;
use App\Models\Teacher\TeacherDetail;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class Teacher extends Component
{
    use WireUiActions, WithFileUploads;

    public $states, $dob, $teachers = [];
    public $teacherName = '';
    public $teacherEmail = '';
    public $teacherMobile = '';
    public $teacherGender = '';
    public $teacherActive = 0;
    public $cities = [];
    public $selectedState = null;
    public $selectedCity = null;
    public $open = false;
    public $showViewModal = false;
    public $openImage = false;
    public $imagePath = null;
    public $editId = null;
    public $teacherImage;
    public $teacherDetailImage;
    public $viewModalTitle = '';
    public $viewData = [];
    public $activeTab = 'teacher';

    // Teacher details fields
    public $employeeId = '';
    public $dateOfJoining = '';
    public $qualification = '';
    public $address = '';
    public $pincode = '';
    public $emergencyContact = '';
    public $teacherImageUrl;

    protected $listeners = ['onViewTeacherAdmin', 'onEditTeacher', 'onDeleteTeacher', 'onImageClick'];

    public function mount()
    {
        $cityHelper = new CityGetHelper();
        $allStates = $cityHelper->getState();

        // Set states and select first state (Maharashtra) by default
        $this->states = $allStates;
        $this->selectedState = count($allStates) > 0 ? null : null;

        // Load cities for default selected state
        if ($this->selectedState) {
            $this->cities = $cityHelper->cityGetByState($this->selectedState);
        } else {
            $this->cities = [];
        }
    }

    public function updatedSelectedState($value)
    {
        if ($value) {
            $this->cities = (new CityGetHelper())->cityGetByState($value);
            $this->selectedCity = null;
        } else {
            $this->cities = [];
            $this->selectedCity = null;
        }
    }

    public function onAddTeacher()
    {
        $this->open = true;
    }

   
    public function closeModal()
    {
        $this->open = false;
        $this->resetForm();
        $this->dispatch('onUserAddUpdate');
    }

    public function onSave()
    {
        $rules = [
            'teacherName' => 'required|string|max:255',
            'teacherEmail' => 'required|email|max:50|unique:users,email,' . ($this->editId ?? ''),
            'teacherMobile' => 'required|string|max:15',
            'dob' => 'required|date',
            'teacherGender' => 'required|string|in:male,female,other',
            'employeeId' => 'required|string|max:50',
            'dateOfJoining' => 'required|date',
            'qualification' => 'required|string|max:255',
            'address' => 'required|string',
            'pincode' => 'required|digits:6',
            'emergencyContact' => 'required|string|max:15',
            'teacherImage' => 'nullable|image|max:2048', // 2MB max
            'teacherDetailImage' => 'nullable|image|max:2048', // 2MB max
        ];

        $this->validate($rules);

        try {
            $teacher = $this->editId ? User::find($this->editId) : new User();

            $userData = [
                'name' => $this->teacherName,
                'email' => $this->teacherEmail,
                'mobile_number' => $this->teacherMobile,
                'dob' => $this->dob,
                'gender' => $this->teacherGender,
                'role' => 'teacher',
                'is_active' => $this->teacherActive,
                'organization_id' => Auth::user()->organization_id
            ];

              if ($this->teacherImage) {
                // Delete old image if exists
                if ($teacher->image) {
                    $oldImagePath = parse_url($teacher->image, PHP_URL_PATH);
                    Storage::disk('s3')->delete($oldImagePath);
                }

                $imagePath = $this->teacherImage->store('teacher-images', 's3');
                Storage::disk('s3')->setVisibility($imagePath, 'public');
                $userData['image'] = Storage::disk('s3')->url($imagePath);            }


            if (!$this->editId) {
                $userData['password'] = Hash::make('12345678');
            }

            $teacher->fill($userData);
            $teacher->save();

            $teacherDetailData = [
                'user_id' => $teacher->id,
                'organization_id' => Auth::user()->organization_id,
                'employee_id' => $this->employeeId,
                'date_of_joining' => $this->dateOfJoining,
                'qualification' => $this->qualification,
                'phone' => $this->teacherMobile,
                'address' => $this->address,
                'city' => $this->selectedCity,
                'state' => $this->selectedState,
                'pincode' => $this->pincode,
                'emergency_contact' => $this->emergencyContact,
            ];

            TeacherDetail::updateOrCreate(
                ['user_id' => $teacher->id],
                $teacherDetailData
            );

            $this->notification()->success(
                $this->editId ? 'Teacher Updated Successfully!' : 'Teacher Created Successfully!'
            );

            $this->resetForm();
            $this->dispatch('onTeacherAddUpdate');
        } catch (\Exception $e) {
            $this->notification()->error(
                'Error Saving Teacher',
                $e->getMessage()
            );
        }
    }

    protected function resetForm()
    {
        $this->reset([
            'teachers',
            'dob',
            'selectedState',
            'selectedCity',
            'teacherName',
            'teacherEmail',
            'teacherMobile',
            'teacherGender',
            'employeeId',
            'dateOfJoining',
            'qualification',
            'address',
            'pincode',
            'emergencyContact',
            'teacherActive',
            'editId'
        ]);
        $this->open = false;
    }

     public function closeImage()
    {
        $this->openImage = false;
    }


    public function onEditTeacher($id){

        $teacherDetail = TeacherDetail::find($id);

        if (!$teacherDetail) {
            abort(404, 'Teacher detail not found');
        }

        $user = User::find($teacherDetail->user_id);

        if (!$user) {
            abort(404, 'User not found');
        }

        $this->teachers = $user->toArray();
        $this->editId = $user->id;
        $this->teacherName = $user->name;
        $this->teacherEmail = $user->email;
        $this->teacherMobile = $user->mobile_number;
        $this->teacherActive = $user->is_active;

        $this->dob = $user->dob;
        $this->address = $teacherDetail->address;
        $this->employeeId = $teacherDetail->employee_id;
        $this->dateOfJoining = $teacherDetail->date_of_joining;
        $this->emergencyContact = $teacherDetail->emergency_contact;
        $this->qualification = $teacherDetail->qualification;
        $this->selectedState = $teacherDetail->state;
        $this->selectedCity = $teacherDetail->city;
        $this->pincode = $teacherDetail->pincode;

        $this->teacherImageUrl = $user->image;
        

        if ($this->selectedState) {
            $this->cities = (new CityGetHelper())->cityGetByState($this->selectedState);
        }

        $this->open = true;
        $this->dispatch('onUserAddUpdate');
    }

    public function onDeleteTeacher($id)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'icon' => 'exclamation-circle',
            'iconColor' => 'text-red-500',
            'description' => 'Are you sure you want to delete this user, The action cannot be undone?',
            'accept' => [
                'label' => 'Yes, delete it',
                'method' => 'doDeleteTeacher',
                'params' => $id,
                'color' => 'negative',
                'size' => 'md',
            ],
            'reject' => [
                'label' => 'No',
                'size' => 'md',
            ],
        ]);
    }

    public function doDeleteTeacher($id)
    {
        $teacherDetail = TeacherDetail::find($id);

        if ($teacherDetail) {
            $userDelete = User::find($teacherDetail->user_id);

            if ($userDelete) {
                $teacherDetail->delete();

                $userDelete->delete();

                $this->notification()->success('Teacher Deleted Successfully!');
                $this->dispatch('onUserAddUpdate');
            } else {
                $this->notification()->error('User not found!');
            }
        } else {
            $this->notification()->error('Teacher detail not found!');
        }
    }

    public function onViewTeacherAdmin($id)
    {
        $teacherDetail = TeacherDetail::find($id);

        $user = User::find($teacherDetail->user_id);

        if (!$teacherDetail || !$teacherDetail->user) {
            $this->notification()->error('Teacher not found!');
            return;
        }
        $this->teacherImageUrl = $user->image;
        $this->viewModalTitle = 'Teacher Details';
        $this->activeTab = 'teacher';

        $this->viewData = [
            'user' => $teacherDetail->user,
            'detail' => $teacherDetail,
        ];

        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->viewData = [];
        $this->viewModalTitle = '';
    }

    public function render()
    {
        return view('livewire.admin.teacher');
    }
}
