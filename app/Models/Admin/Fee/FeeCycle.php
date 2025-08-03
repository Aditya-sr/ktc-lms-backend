<?php

namespace App\Models\Admin\Fee;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;

class FeeCycle extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'name',
        'frequency',
        'installments_count',
        'due_day_of_month',
        'organization_id',
        'is_active',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function templates()
    {
        return $this->hasMany(FeeTemplate::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function getFrequencyOptions()
    {
        return [
            'yearly' => 'Yearly',
            'half_yearly' => 'Half Yearly',
            'quarterly' => 'Quarterly',
            'monthly' => 'Monthly',
            'custom' => 'Custom'
        ];
    }
}
