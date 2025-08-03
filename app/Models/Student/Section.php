<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['standard_id', 'name', 'image', 'description', 'is_active'];

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
}
