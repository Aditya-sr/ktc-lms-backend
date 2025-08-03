<?php

namespace App\Models\Student;

use App\Models\Admin\StudentIdCard;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StudentDetail extends Model
{
    protected $fillable = [
        'user_id',
        'standard_id',
        'section_id',
        'full_name',
        'father_name',
        'mother_name',
        'email',
        'dob',
        'gender',
        'religion',
        'local_address',
        'permanent_address',
        'city',
        'state',
        'pincode',
        'admission_no',
        'date_of_admission',
        'roll_no',
        'board',
        'aadhar_no',
        'phone',
        'image',
        'organization_id'
    ];

    protected $casts = [
        'date_of_admission' => 'date',
        'dob' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function idCards()
    {
        return $this->hasMany(StudentIdCard::class);
    }
}
