<?php

namespace App\Models\Admin;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;

class AdminEnquiry extends Model
{
    protected $fillable = ['organization_id', 'full_name', 'type', 'email', 'mobile_number', 'description'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
