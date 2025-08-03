<?php

namespace App\Livewire\Admin;

use App\Helpers\CityGetHelper;
use App\Models\Admin\SchoolInfo;
use App\Models\Student\Section;
use App\Models\Student\Standard;
use App\Models\Student\StudentDetail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

class Student extends Component
{
    use WireUiActions, WithFileUploads;

    public $states, $dob, $students = [];
    public $studentsName = '';
    public $studentsEmail = '';
    public $studentsMobile = '';
    public $studentsGender = '';
    public $studentsBoard = '';
    public $studentsClass = '';
    public $studentsSection = '';
    public $studentsActive = 0;
    public $cities = [];
    public $selectedState = null;
    public $selectedCity = null;
    public $open = false;
    public $openImage = false;
    public $showViewModal = false;
    public $imagePath = null;
    public $editId = null;
    public $studentImage;
    public $studentDetailImage;
    public $studentImageUrl;

    // Additional student details fields
    public $fatherName = '';
    public $motherName = '';
    public $religion = '';
    public $localAddress = '';
    public $permanentAddress = '';
    public $pincode = '';
    public $aadharNo = '';
    public $dateOfAdmission = '';
    public $sections = [];
    public $standards;

    public $viewModalTitle = '';
    public $viewData = [];
    public $activeTab = 'student';

    protected $listeners = ['onViewStudentAdmin', 'onEditStudent', 'onDeleteStudent', 'onImageClick'];

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

        $this->standards = Standard::where('organization_id', Auth::user()->organization_id)->get();
        $this->loadSections();
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

    public function loadSections()
    {
        if ($this->studentsClass) {
            $standardId = is_object($this->studentsClass) ? $this->studentsClass->id : $this->studentsClass;

            $this->sections = Section::where('standard_id', $standardId)->get();
        } else {
            $this->sections = [];
        }
    }

    public function updatedStudentsClass()
    {
        $this->loadSections();
    }

    public function onAddStudent()
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
        // Validation rules
        $rules = [
            'studentsName' => 'required|string|max:255',
            'studentsEmail' => 'required|email|max:50',
            'studentsMobile' => 'required|string|max:15',
            'dob' => 'required|date|before:today',
            'studentsGender' => 'required|string|in:male,female,other',
            'studentsBoard' => 'required|string',
            'studentsClass' => 'integer|exists:standards,id',
            'studentsSection' => 'integer|exists:sections,id',
            'fatherName' => 'required|string|max:255',
            'motherName' => 'required|string|max:255',
            'dateOfAdmission' => 'required|date|after_or_equal:today',
            'aadharNo' => 'nullable|digits:12',
            'pincode' => 'nullable|digits:6',
            'studentImage' => 'nullable|image|max:2048', // 2MB max
            'studentDetailImage' => 'nullable|image|max:2048', // 2MB max
        ];

        if (empty($this->students['id'])) {
            $rules['studentsEmail'] .= '|unique:users,email';
        }

        $this->validate($rules);

        try {
            $student = !empty($this->students['id']) ? User::find($this->students['id']) : new User();

            $studentData = [
                'name' => $this->studentsName,
                'email' => $this->studentsEmail,
                'mobile_number' => $this->studentsMobile,
                'role' => 'user',
                'is_active' => $this->studentsActive ?? 0,
                'organization_id' => Auth::user()->organization_id
            ];

            // Handle student image upload
            if ($this->studentImage) {
                // Delete old image if exists
                if ($student->image) {
                    $oldImagePath = parse_url($student->image, PHP_URL_PATH);
                    Storage::disk('s3')->delete($oldImagePath);
                }

                $imagePath = $this->studentImage->store('student-images', 's3');
                Storage::disk('s3')->setVisibility($imagePath, 'public');
                $studentData['image'] = Storage::disk('s3')->url($imagePath);
            }

            if (empty($this->students['id'])) {
                $studentData['password'] = Hash::make($this->studentsPassword ?? '123456789');
            }
            $student->fill($studentData);
            $student->save();

            // Prepare student detail data
            $studentDetailData = [
                'user_id' => $student->id,
                'standard_id' => (int)$this->studentsClass,
                'section_id' => (int)$this->studentsSection,
                'full_name' => $this->studentsName,
                'father_name' => $this->fatherName,
                'mother_name' => $this->motherName,
                'email' => $this->studentsEmail,
                'dob' => $this->dob,
                'gender' => $this->studentsGender,
                'religion' => $this->religion ?? null,
                'local_address' => $this->localAddress ?? null,
                'permanent_address' => $this->permanentAddress ?? null,
                'city' => $this->selectedCity ?? null,
                'state' => $this->selectedState ?? null,
                'pincode' => $this->pincode ?? null,
                'admission_no' => $this->generateAdmissionNumber(),
                'date_of_admission' => $this->dateOfAdmission,
                'roll_no' => $this->generateRollNumber(),
                'board' => $this->studentsBoard,
                'aadhar_no' => $this->aadharNo ?? null,
                'phone' => $this->studentsMobile,
            ];

            // Save to StudentDetail table
            if (!empty($this->students['id'])) {
                $studentDetail = StudentDetail::updateOrCreate(
                    ['user_id' => $student->id],
                    $studentDetailData
                );
                $this->notification()->success('Student Updated Successfully!');
            } else {
                $studentDetail = StudentDetail::create($studentDetailData);
                $this->notification()->success('Student Created Successfully!');
            }

            $this->resetForm();
            $this->dispatch('onUserAddUpdate');
        } catch (\Exception $e) {
            $this->notification()->error(
                'Error Saving Student',
                $e->getMessage()
            );
            logger()->error('Student save error: ' . $e->getMessage());
        }
    }

    protected function generateAdmissionNumber()
    {
        $year = date('Y');
        $schoolCode = Auth::user()->organization->school_code;
        $class = $this->studentsClass;
        $section = $this->studentsSection;

        $lastAdmission = StudentDetail::where('standard_id', $class)
            ->where('section_id', $section)
            ->where('admission_no', 'like', "$year$schoolCode$class$section%")
            ->orderBy('admission_no', 'desc')
            ->first();

        $serialNo = $lastAdmission ? (int)substr($lastAdmission->admission_no, -4) + 1 : 1;
        $serialNoFormatted = str_pad($serialNo, 4, '0', STR_PAD_LEFT);

        return $year . $schoolCode . $class . $section . $serialNoFormatted;
    }

    protected function generateRollNumber()
    {
        $shortYear = substr(date('Y'), -2);
        $schoolSerial = '01';
        $classFormatted = str_pad($this->studentsClass, 2, '0', STR_PAD_LEFT);
        $sectionCode = substr($this->studentsSection, 0, 1);

        $lastRollNumber = StudentDetail::where('standard_id', $this->studentsClass)
            ->where('section_id', $this->studentsSection)
            ->where('roll_no', 'like', "$shortYear$schoolSerial$classFormatted$sectionCode%")
            ->orderBy('roll_no', 'desc')
            ->first();

        $serialNo = $lastRollNumber ? (int)substr($lastRollNumber->roll_no, -3) + 1 : 1;
        $serialNoFormatted = str_pad($serialNo, 3, '0', STR_PAD_LEFT);

        return $shortYear . $schoolSerial . $classFormatted . $sectionCode . $serialNoFormatted;
    }

    protected function resetForm()
    {
        $this->reset([
            'students',
            'dob',
            'selectedState',
            'selectedCity',
            'studentsName',
            'studentsEmail',
            'studentsMobile',
            'studentsGender',
            'studentsBoard',
            'studentsClass',
            'studentsSection',
            'fatherName',
            'motherName',
            'religion',
            'localAddress',
            'permanentAddress',
            'pincode',
            'aadharNo',
            'dateOfAdmission'
        ]);
        $this->open = false;
    }

    public function onImageClick($id)
    {
        $image = User::find($id);
        $this->imagePath = $image->image;
        $this->openImage = true;
    }

    public function closeImage()
    {
        $this->openImage = false;
    }

    public function onEditStudent($id)
    {
        $studentDetail = StudentDetail::find($id);

        if (!$studentDetail) {
            abort(404, 'Student detail not found');
        }

        $user = User::find($studentDetail->user_id)?->refresh();

        if (!$user) {
            abort(404, 'User not found');
        }

        $this->students = $user->toArray();
        $this->editId = $user->id;
        $this->studentsName = $user->name;
        $this->studentsEmail = $user->email;
        $this->studentsMobile = $user->mobile_number;
        $this->studentsActive = $user->is_active;

        $this->dob = $studentDetail->dob;
        $this->fatherName = $studentDetail->father_name;
        $this->motherName = $studentDetail->mother_name;
        $this->studentsGender = $studentDetail->gender;
        $this->studentsBoard = $studentDetail->board;
        $this->studentsClass = $studentDetail->standard_id;
        $this->studentsSection = $studentDetail->section_id;
        $this->religion = $studentDetail->religion;
        $this->localAddress = $studentDetail->local_address;
        $this->permanentAddress = $studentDetail->permanent_address;
        $this->selectedState = $studentDetail->state;
        $this->selectedCity = $studentDetail->city;
        $this->pincode = $studentDetail->pincode;
        $this->aadharNo = $studentDetail->aadhar_no;
        $this->dateOfAdmission = $studentDetail->date_of_admission;

        $this->studentImageUrl = $user->image;

        if ($this->selectedState) {
            $this->cities = (new CityGetHelper())->cityGetByState($this->selectedState);
        }

        $this->open = true;
        $this->dispatch('onUserAddUpdate');
    }

    public function onDeleteStudent($id)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'icon' => 'exclamation-circle',
            'iconColor' => 'text-red-500',
            'description' => 'Are you sure you want to delete this user, The action cannot be undone?',
            'accept' => [
                'label' => 'Yes, delete it',
                'method' => 'doDeleteStudent',
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

    public function doDeleteStudent($id)
    {
        $studentDetail = StudentDetail::find($id);

        if ($studentDetail) {
            $userDelete = User::find($studentDetail->user_id);

            if ($userDelete) {
                $studentDetail->delete();

                $userDelete->delete();

                $this->notification()->success('Student Deleted Successfully!');
                $this->dispatch('onUserAddUpdate');
            } else {
                $this->notification()->error('User not found!');
            }
        } else {
            $this->notification()->error('Student detail not found!');
        }
    }

    public function onViewStudentAdmin($id)
    {
        $studentDetail = StudentDetail::with(['user', 'standard', 'section'])->find($id);

        if (!$studentDetail || !$studentDetail->user) {
            $this->notification()->error('Student not found!');
            return;
        }

        $this->studentImageUrl = $studentDetail->user->image;
        $this->viewModalTitle = 'Student Details';
        $this->activeTab = 'student';

        $this->viewData = [
            'user' => $studentDetail->user,
            'detail' => $studentDetail,
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
        return view('livewire.admin.student');
    }
}
