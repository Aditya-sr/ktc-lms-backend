<?php

namespace App\Livewire\Admin;

use App\Models\Admin\Exam;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class AddExam extends Component
{
    use WireUiActions;

    public $exam = [];
    public $examName = '';
    public $academicYear = '';
    public $startDate = '';
    public $endDate = '';
    public $description = '';
    public $isPublished = false;
    public $examType = '';
    public $totalMarks = '';
    public $passingMarks = '';
    public $usesGradingSystem = false;
    public $editId = null;
    public $showViewModal = false;
    public $open = false;
    public $viewModalTitle = '';
    public $viewData = [];
    public $academicYearOptions = [];

    public $examTypes = [
        'quarterly' => 'Quarterly',
        'half_yearly' => 'Half Yearly',
        'annual' => 'Annual',
        'unit_test' => 'Unit Test',
        'pre_board' => 'Pre Board'
    ];

    protected $listeners = ['onViewExam', 'onEditExam', 'onDeleteExam'];

    public function mount()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;

        // Generate academic year options
        $this->academicYearOptions = [
            $currentYear . '-' . $nextYear,
            $nextYear . '-' . ($nextYear + 1)
        ];

        // Set default to current academic year
        $this->academicYear = $currentYear . '-' . $nextYear;
    }

    public function onAddExam()
    {
        $this->resetForm();
        $this->open = true;
        $this->editId = null;
    }

    public function onSave()
    {
        $rules = [
            'examName' => 'required|string|max:255',
            'academicYear' => 'required|string|max:9',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'examType' => 'required|string',
            'usesGradingSystem' => 'boolean',
        ];

        if ($this->usesGradingSystem) {
        } else {
            $rules['totalMarks'] = 'required|integer|min:1';
            $rules['passingMarks'] = 'required|integer|min:1|lt:totalMarks';
        }

        $this->validate($rules);

        try {
            $examData = [
                'organization_id' => Auth::user()->organization_id,
                'exam_name' => $this->examName,
                'academic_year' => $this->academicYear,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'description' => $this->description,
                'is_published' => $this->isPublished,
                'exam_type' => $this->examType,
                'uses_grading_system' => $this->usesGradingSystem,
                'total_marks' => $this->usesGradingSystem ? null : $this->totalMarks,
                'passing_marks' => $this->usesGradingSystem ? null : $this->passingMarks,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];

            if ($this->editId) {
                $exam = Exam::findOrFail($this->editId);
                $exam->update($examData);
                $this->notification()->success('Exam Updated Successfully!');
            } else {
                Exam::create($examData);
                $this->notification()->success('Exam Created Successfully!');
            }

            $this->resetForm();
            $this->dispatch('onExamAddUpdate');
        } catch (\Exception $e) {
            $this->notification()->error(
                'Error Saving Exam',
                $e->getMessage()
            );
        }
    }

    protected function resetForm()
    {
        $this->reset([
            'examName',
            'academicYear',
            'startDate',
            'endDate',
            'description',
            'isPublished',
            'examType',
            'totalMarks',
            'passingMarks',
            'usesGradingSystem',
            'editId'
        ]);
        $this->open = false;
    }

    public function onEditExam($id)
    {
        $exam = Exam::findOrFail($id);
        $this->editId = $exam->id;
        $this->examName = $exam->exam_name;
        $this->academicYear = $exam->academic_year;
        $this->startDate = $exam->start_date->format('Y-m-d');
        $this->endDate = $exam->end_date->format('Y-m-d');
        $this->description = $exam->description;
        $this->isPublished = $exam->is_published;
        $this->examType = $exam->exam_type;
        $this->totalMarks = $exam->total_marks;
        $this->passingMarks = $exam->passing_marks;
        $this->usesGradingSystem = $exam->uses_grading_system;
    }

    public function onViewExam($id)
    {
        $exam = Exam::with(['createdBy', 'updatedBy'])->findOrFail($id);

        $this->viewModalTitle = 'Exam Details';
        $this->viewData = [
            'exam' => $exam,
            'details' => [
                'Exam Name' => $exam->exam_name,
                'Academic Year' => $exam->academic_year,
                'Start Date' => $exam->start_date->format('d-m-Y'),
                'End Date' => $exam->end_date->format('d-m-Y'),
                'Exam Type' => $this->examTypes[$exam->exam_type] ?? $exam->exam_type,
                'Evaluation System' => $exam->uses_grading_system ? 'Grading System' : 'Marks System',
                'Total Marks' => $exam->uses_grading_system ? 'N/A' : $exam->total_marks,
                'Passing Marks' => $exam->uses_grading_system ? 'N/A' : $exam->passing_marks,
                'Grading System' => $exam->uses_grading_system ? $exam->gradeSystem->name : 'N/A',
                'Status' => ucfirst($exam->status),
                'Created By' => $exam->createdBy->name,
                'Created At' => $exam->created_at->format('d-m-Y H:i'),
                'Last Updated' => $exam->updated_at->format('d-m-Y H:i'),
            ]
        ];

        $this->showViewModal = true;
    }

    public function onDeleteExam($id)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Are you sure you want to delete this exam? This action cannot be undone.',
            'icon' => 'error',
            'accept' => [
                'label' => 'Yes, delete it',
                'method' => 'doDeleteExam',
                'params' => $id,
            ],
            'reject' => [
                'label' => 'No, cancel',
            ],
        ]);
    }

    public function doDeleteExam($id)
    {
        try {
            $exam = Exam::findOrFail($id);
            $exam->delete();
            $this->notification()->success('Exam Deleted Successfully!');
            $this->dispatch('onExamAddUpdate');
        } catch (\Exception $e) {
            $this->notification()->error('Error Deleting Exam', $e->getMessage());
        }
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
    }

    public function render()
    {
        return view('livewire.admin.add-exam');
    }
}
