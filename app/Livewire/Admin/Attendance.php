<?php

namespace App\Livewire\Admin;

use App\Models\Student\Section;
use App\Models\Student\Standard;
use App\Models\Teacher\AssignTeacherStandard;
use App\Models\Teacher\TeacherDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Attendance extends Component
{
    use WireUiActions;

    public $activeTab = 'assign_teacher_class';
    public $subTab = 'assign_teacher';

    // For the form
    public $showModalAssignTeacher = false;
    public $editId = null;
    public $teacher_detail_id;
    public $standard_id;
    public $section_id;

    // For filtering
    public $selectedStandard;
    public $filteredSections = [];

    // Data collections
    public $teachers = [];
    public $standards = [];
    public $sections;
    public $assignments;

    public $tabs = [
        'teacher_attendance' => [
            'teacher_attendance' => 'Attendance',
            'teacher_attendance_list' => 'Attendance List',
            'teacher_attendance_dashboard' => 'Dashboard',
        ],
        'student_attendance' => [
            'student_attendance_list' => 'Attendance List',
            'student_attendance_dashboard' => 'Dashboard',
        ],
        'assign_teacher_class' => [
            'assign_teacher' => 'Assign Teacher',
        ]
    ];

    public function mount()
    {
        $this->loadData();
    }

    protected function loadData()
    {
        $orgId = Auth::user()->organization_id;

        $this->standards = Standard::where('organization_id', $orgId)->get();
        $this->sections = Section::whereHas('standard', fn($q) => $q->where('organization_id', $orgId))->get();
        $this->teachers = $this->loadAvailableTeachers(); // Now a collection
    }

    protected function loadAvailableTeachers()
    {
        $assignedTeacherIds = AssignTeacherStandard::where('organization_id', Auth::user()->organization_id)
            ->when($this->editId, function ($q) {
                $q->where('id', '!=', $this->editId);
            })
            ->pluck('teacher_detail_id')
            ->toArray();

        // Return collection instead of array
        return TeacherDetail::with('user')
            ->where('organization_id', Auth::user()->organization_id)
            ->whereNotIn('id', $assignedTeacherIds)
            ->get();
    }

    public function updatedStandardId($value)
    {
        $this->filteredSections = Section::where('standard_id', $value)
            ->get()
            ->map(function ($section) {
                return [
                    'value' => $section->id,
                    'label' => $section->name,
                ];
            })
            ->toArray();

        $this->section_id = null;
    }

    public function openModalAssignTeacher()
    {
        $this->showModalAssignTeacher = true;
    }

    public function closeModalAssignTeacher()
    {
        $this->showModalAssignTeacher = false;
        $this->resetForm();
    }

    public function onEdit($id)
    {
        $this->editId = $id;
        $assignment = AssignTeacherStandard::find($id);

        $this->teacher_detail_id = $assignment->teacher_detail_id;
        $this->standard_id = $assignment->standard_id;
        $this->section_id = $assignment->section_id;

        // Load sections for the selected standard
        $this->updatedStandardId($this->standard_id);

        // Reload teachers to include the current one
        $this->loadAvailableTeachers();

        $this->showModalAssignTeacher = true;
    }

    public function saveAssignment()
    {
        $this->validate([
            'teacher_detail_id' => 'required|exists:teacher_details,id',
            'standard_id' => 'required|exists:standards,id',
            'section_id' => [
                'integer',
                Rule::when($this->section_id != 0, 'exists:sections,id')
            ],
        ]);

        // Check if this teacher is already assigned to this class-section combo
        $existing = AssignTeacherStandard::where('teacher_detail_id', $this->teacher_detail_id)
            ->where('standard_id', $this->standard_id)
            ->when($this->section_id, function ($query) {
                $query->where('section_id', $this->section_id);
            })
            ->when($this->editId, function ($query) {
                $query->where('id', '!=', $this->editId);
            })
            ->exists();

        if ($existing) {
            $this->notification()->error(
                $this->section_id
                    ? 'This teacher is already assigned to this class-section!'
                    : 'This teacher is already assigned to this class!'
            );
            return;
        }

        $data = [
            'teacher_detail_id' => $this->teacher_detail_id,
            'standard_id' => $this->standard_id,
            'section_id' => $this->section_id,
            'organization_id' => Auth::user()->organization_id,
        ];

        if ($this->editId) {
            AssignTeacherStandard::find($this->editId)->update($data);
            $message = 'Assignment updated successfully!';
        } else {
            AssignTeacherStandard::create($data);
            $message = 'Teacher assigned successfully!';
        }

        $this->closeModalAssignTeacher();
        $this->loadData();
        $this->notification()->success($message);
    }

    public function onDelete($id)
    {
        AssignTeacherStandard::find($id)->delete();
        $this->loadData();
        $this->notification()->success('Assignment deleted successfully!');
    }

    private function resetForm()
    {
        $this->reset([
            'teacher_detail_id',
            'standard_id',
            'section_id',
            'editId',
            'filteredSections'
        ]);
    }

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
        return view('livewire.admin.attendance');
    }
}
