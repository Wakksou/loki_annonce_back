<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class annonce extends Model
{
    protected $fillable = [
        'title',
        'category',
        'image',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_annonce')->withTimestamps();
    }

}
