<?php

namespace App\Models\Admin\Fee;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'fee_cycle_id',
        'organization_id',
        'is_active',
        'is_default',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'metadata' => 'array',
    ];

    public function cycle()
    {
        return $this->belongsTo(FeeCycle::class, 'fee_cycle_id');
    }

    public function items()
    {
        return $this->hasMany(FeeTemplateItem::class)->orderBy('sort_order');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function assignments()
    {
        return $this->hasMany(FeeAssignment::class);
    }

    public function calculateTotal()
    {
        return $this->items()->sum('amount');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
