<?php

namespace App\Models\Admin\Fee;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;

class FeeConcession extends Model
{
    protected $fillable = [
        'name',
        'code',
        'amount',
        'type',
        'is_active',
        'description',
        'organization_id',
        'eligibility_criteria'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'eligibility_criteria' => 'array',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function feeAssignments()
    {
        return $this->hasMany(FeeAssignment::class);
    }

    public function calculateConcessionAmount($baseAmount)
    {
        return $this->type === 'percentage'
            ? $baseAmount * ($this->amount / 100)
            : min($this->amount, $baseAmount);
    }
}
