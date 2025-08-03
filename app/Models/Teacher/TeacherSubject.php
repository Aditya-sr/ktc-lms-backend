<?php

namespace App\Models\Teacher;

use App\Models\Student\Standard;
use App\Models\Student\Subject;
use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    protected $fillable = ['teacher_detail_id', 'section_id', 'standard_id', 'organization_id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
}
