<?php

namespace App\Models\Admin;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;

class RateLms extends Model
{
    protected $fillable = [
        'organization_id',
        'feedback',
        'rating',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
