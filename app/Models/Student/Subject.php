<?php

namespace App\Models\Student;

use App\Models\Organization;
use App\Models\Stream;
use App\Models\Teacher\TeacherSubject;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'streams_id', 'code', 'organization_id', 'description', 'is_active'];

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

    public function stream()
    {
        return $this->belongsTo(Stream::class, 'stream_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
