<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    protected $fillable = ['name', 'code', 'organization_id', 'file_path', 'board', 'order', 'is_active'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'standard_subjects')
            ->withPivot('is_mandatory')
            ->withTimestamps();
    }

    
}
