<?php

namespace App\Models;

use App\Models\Admin\SchoolInfo;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'state',
        'education_board',
        'school_code',
        'serial_number',
        'status',
        'logo',
        'address'
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
    protected $table = 'organizations';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function schoolInfo()
    {
        return $this->belongsTo(SchoolInfo::class); 
    }
}
