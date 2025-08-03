<?php

namespace App\Models\Admin;

use App\Models\Teacher\TeacherDetail;
use Illuminate\Database\Eloquent\Model;

class TeacherArrangement extends Model
{
    protected $fillable = [
        'original_teacher_id',
        'substitute_teacher_id',
        'timetable_id',
        'date',
        'reason'
    ];

    public function originalTeacher()
    {
        return $this->belongsTo(TeacherDetail::class, 'original_teacher_id');
    }

    public function substituteTeacher()
    {
        return $this->belongsTo(TeacherDetail::class, 'substitute_teacher_id');
    }

    public function timetable()
    {
        return $this->belongsTo(TeacherTimeTable::class);
    }
}
