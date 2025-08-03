<?php

namespace App\Models\Admin;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    protected $fillable = ['title', 'author', 'publisher', 'publication_year', 'isbn', 'edition', 'category', 'description', 'language', 'pages', 'cover_image', 'file_path', 'type', 'availability', 'user_id', 'organization_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
