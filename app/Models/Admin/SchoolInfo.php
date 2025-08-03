<?php

namespace App\Models\Admin;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolInfo extends Model
{
    protected $fillable = [
        'organization_id', 
        'about_school', 
        'website_info', 
        'website_url',
        'school_mobile',
        'school_email',
        'school_address'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function managementTeam()
    {
        return $this->hasMany(SchoolManagementTeam::class)->orderBy('sort_order');
    }

    public function documents()
    {
        return $this->hasMany(SchoolDocument::class)->orderBy('sort_order');
    }
}