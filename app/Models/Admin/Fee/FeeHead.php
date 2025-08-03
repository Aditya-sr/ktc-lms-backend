<?php

namespace App\Models\Admin\Fee;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeHead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'is_active',
        'type',
        'is_refundable',
        'is_taxable',
        'organization_id',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_refundable' => 'boolean',
        'is_taxable' => 'boolean',
        'metadata' => 'array',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function templateItems()
    {
        return $this->hasMany(FeeTemplateItem::class);
    }

    public function feeAssignments()
    {
        return $this->hasManyThrough(
            FeeAssignment::class,
            FeeTemplateItem::class,
            'fee_head_id',
            'fee_template_id',
            'id',
            'fee_template_id'
        );
    }
}
