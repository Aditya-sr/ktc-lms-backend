<?php

namespace App\Models\Admin;

use App\Models\Organization;
use App\Models\Student\StudentDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ContactAdminStudent extends Model
{
    protected $fillable = ['student_detail_id', 'user_id', 'organization_id', 'topic', 'student_query', 'image', 'admin_text', 'admin_reply'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentDetail()
    {
        return $this->belongsTo(StudentDetail::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
