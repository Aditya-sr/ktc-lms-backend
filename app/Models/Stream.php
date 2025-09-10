<?php

namespace App\Models;

use App\Models\Student\Subject;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    protected $fillable = ['name'];

    public function subjects()  {
        return $this->hasMany(Subject::class, 'stream_id');
        
    }
}
