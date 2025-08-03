<?php

namespace App\Livewire\Admin;

use App\Models\Admin\TeacherArrangement;
use App\Models\Admin\TeacherTimeTable;
use Livewire\Component;
use App\Models\Teacher\TeacherAttendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Arrangement extends Component
{
    public $date;
    public $selectedTeacherId;
    public $selectedTimetableId;
    public $substituteTeacherId;
    public $reason;

    public $teachers = [];
    public $absentTeachers = [];
    public $teacherTimetables = [];
    public $availableSubstitutes = [];
    public $existingArrangements = [];

    protected $rules = [
        'selectedTeacherId' => 'required|exists:users,id',
        'selectedTimetableId' => 'required|exists:teacher_timetables,id',
        'substituteTeacherId' => 'required|exists:users,id',
        'reason' => 'nullable|string|max:500',
        'date' => 'required|date|after_or_equal:today',
    ];

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->loadAbsentTeachers();
        $this->loadExistingArrangements();
    }

    public function loadAbsentTeachers()
    {
        // Get teachers marked absent today
        $this->absentTeachers = TeacherAttendance::whereDate('attendance_date', $this->date)
            ->where('status', 'absent')
            ->with('teacher')
            ->get()
            ->pluck('teacher')
            ->unique();

        $this->teachers = User::where('role' ,'teacher')->with('teacherDetail')->get();
    }

    public function loadExistingArrangements()
    {
        $this->existingArrangements = TeacherArrangement::whereDate('date', $this->date)
            ->with(['originalTeacher', 'substituteTeacher', 'timetable.standard', 'timetable.section', 'timetable.subject'])
            ->get();
    }

    public function updatedSelectedTeacherId($teacherId)
    {
        $this->teacherTimetables = TeacherTimeTable::where('teacher_id', $teacherId)
            ->with(['standard', 'section', 'subject'])
            ->get();

        $this->availableSubstitutes = User::role('teacher')
            ->where('id', '!=', $teacherId)
            ->whereDoesntHave('teacherAttendance', function ($query) {
                $query->whereDate('date', $this->date)
                    ->where('status', 'absent');
            })
            ->with('teacherDetail')
            ->get();
    }

    public function updatedDate()
    {
        $this->loadAbsentTeachers();
        $this->loadExistingArrangements();
    }

    public function createArrangement()
    {
        $this->validate();

        try {
            TeacherArrangement::create([
                'original_teacher_id' => $this->selectedTeacherId,
                'substitute_teacher_id' => $this->substituteTeacherId,
                'timetable_id' => $this->selectedTimetableId,
                'date' => $this->date,
                'reason' => $this->reason,
                'arranged_by' => Auth::id(),
            ]);

            $this->reset(['selectedTeacherId', 'selectedTimetableId', 'substituteTeacherId', 'reason']);
            $this->loadExistingArrangements();
            session()->flash('success', 'Substitute arrangement created successfully');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create arrangement: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.arrangement', [
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
