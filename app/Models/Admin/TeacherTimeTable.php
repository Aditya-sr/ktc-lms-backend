<?php

namespace App\Models\Admin;

use App\Models\Student\Section;
use App\Models\Student\Standard;
use App\Models\Student\Subject;
use App\Models\Teacher\TeacherDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TeacherTimeTable extends Model
{
    protected $fillable = [
        'teacher_detail_id',
        'standard_id',
        'section_id',
        'subject_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
        'assigned_by',
        'effective_from',
        'effective_to'
    ];

    protected $dates = ['effective_from', 'effective_to'];

    public function teacher()
    {
        return $this->belongsTo(TeacherDetail::class);
    }

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function substitutes()
    {
        return $this->hasMany(TeacherArrangement::class, 'teacher_detail_id');
    }

    public function currentSubstitute()
    {
        return $this->substitutes()
            ->whereDate('date', now()->toDateString())
            ->first();
    }
}
