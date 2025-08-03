<?php

namespace App\Models\Admin\Fee;

use Illuminate\Database\Eloquent\Model;

class FeeTemplateItem extends Model
{
    protected $fillable = [
        'fee_template_id',
        'organization_id',
        'fee_head_id',
        'amount',
        'is_optional',
        'is_discount',
        'sort_order',
        'metadata'
    ];

    protected $casts = [
        'is_optional' => 'boolean',
        'is_discount' => 'boolean',
        'metadata' => 'array',
    ];

    public function template()
    {
        return $this->belongsTo(FeeTemplate::class, 'fee_template_id');
    }

    public function head()
    {
        return $this->belongsTo(FeeHead::class, 'fee_head_id');
    }
}
