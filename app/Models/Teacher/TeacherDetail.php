<?php

namespace App\Models\Teacher;

use App\Models\Admin\TeacherTimeTable;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TeacherDetail extends Model
{
    protected $fillable = ['user_id', 'organization_id', 'employee_id', 'date_of_joining', 'qualification', 'phone', 'address', 'city', 'state', 'pincode', 'emergency_contact'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class)->with('subject', 'standard');
    }

    public function teacherSections()
    {
        return $this->hasMany(TeacherSection::class)->with('section');
    }

    public function assignedSubjects()
    {
        return $this->hasMany(TeacherSubject::class)
            ->with(['standard', 'section', 'subject']);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function assignStandard()
    {
        return $this->hasMany(AssignTeacherStandard::class);
    }

    public function timetables()
    {
        return $this->hasMany(TeacherTimeTable::class);
    }
}
