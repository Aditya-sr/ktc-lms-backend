<?php

namespace App\Models\Student;

use App\Models\Teacher\TeacherSubject;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'code', 'organization_id', 'description', 'is_active'];

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subject');
    }

    public function standards()
    {
        return $this->belongsToMany(Standard::class, 'standard_subjects');
    }

    public function teacherAssignments()
    {
        return $this->hasMany(TeacherSubject::class);
    }
}
