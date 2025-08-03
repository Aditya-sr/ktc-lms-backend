<?php

// app/Livewire/Admin/TimeTable.php

namespace App\Livewire\Admin;

use App\Models\Admin\TeacherTimeTable;
use Livewire\Component;
use App\Models\Teacher\TeacherDetail;
use App\Models\Student\Standard;
use App\Models\Student\Section;
use App\Models\Student\Subject;
use Illuminate\Support\Facades\DB;

class TimeTable extends Component
{
    public $activeTab = 'create';
    public $teachers = [];
    public $standards = [];
    public $sections = [];
    public $subjects = [];

    // Form fields
    public $teacherId;
    public $standardId;
    public $sectionId;
    public $subjectId;
    public $dayOfWeek = 1;
    public $startTime;
    public $endTime;

    // Validation rules
    protected $rules = [
        'teacherId' => 'required|exists:teacher_details,id',
        'standardId' => 'required|exists:standards,id',
        'sectionId' => 'required|exists:sections,id',
        'subjectId' => 'required|exists:subjects,id',
        'dayOfWeek' => 'required|integer|between:1,7',
        'startTime' => 'required|date_format:H:i',
        'endTime' => 'required|date_format:H:i|after:startTime',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->teachers = TeacherDetail::with('user')->get();
        $this->standards = Standard::where('is_active', true)->get();
        $this->subjects = Subject::all();
    }

    public function updatedStandardId($value)
    {
        $this->sections = Section::where('standard_id', $value)
            ->where('is_active', true)
            ->get();
    }

    public function showTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function saveTimetable()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Check for teacher availability
            $teacherConflict = TeacherTimeTable::where('teacher_id', $this->teacherId)
                ->where('day_of_week', $this->dayOfWeek)
                ->where(function ($query) {
                    $query->whereBetween('start_time', [$this->startTime, $this->endTime])
                        ->orWhereBetween('end_time', [$this->startTime, $this->endTime])
                        ->orWhere(function ($q) {
                            $q->where('start_time', '<=', $this->startTime)
                                ->where('end_time', '>=', $this->endTime);
                        });
                })
                ->exists();

            if ($teacherConflict) {
                throw new \Exception('Teacher is already assigned during this time slot');
            }

            // Check for section availability
            $sectionConflict = TeacherTimeTable::where('standard_id', $this->standardId)
                ->where('section_id', $this->sectionId)
                ->where('day_of_week', $this->dayOfWeek)
                ->where(function ($query) {
                    $query->whereBetween('start_time', [$this->startTime, $this->endTime])
                        ->orWhereBetween('end_time', [$this->startTime, $this->endTime])
                        ->orWhere(function ($q) {
                            $q->where('start_time', '<=', $this->startTime)
                                ->where('end_time', '>=', $this->endTime);
                        });
                })
                ->exists();

            if ($sectionConflict) {
                throw new \Exception('Section already has a class scheduled during this time');
            }

            // Create the timetable entry
            TeacherTimeTable::create([
                'teacher_id' => $this->teacherId,
                'standard_id' => $this->standardId,
                'section_id' => $this->sectionId,
                'subject_id' => $this->subjectId,
                'day_of_week' => $this->dayOfWeek,
                'start_time' => $this->startTime,
                'end_time' => $this->endTime,
            ]);

            DB::commit();

            $this->resetForm();
            session()->flash('success', 'Timetable entry added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    protected function resetForm()
    {
        $this->reset(['teacherId', 'standardId', 'sectionId', 'subjectId', 'startTime', 'endTime']);
        $this->dayOfWeek = 1;
    }

    public function render()
    {
        $timetableEntries = TeacherTimetable::with(['teacher.user', 'standard', 'section', 'subject'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        return view('livewire.admin.time-table', [
            'timetableEntries' => $timetableEntries,
            'daysOfWeek' => [
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday',
                7 => 'Sunday',
            ],
        ]);
    }
}
