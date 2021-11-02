<?php

namespace App\Models;

use App\Models\User;
use App\Models\Episode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    use Authorizable;
    
    protected $fillable = ['title', 'description'];

    //for the gate
    protected $appends = ['edit'];

    //gate
    public function getEditAttribute()
    {
        return $this->can('edit-course', $this);
    }

    //pass the auth user id to course store method
    protected static function booted()
    {
        static::creating(function ($course)
        {
            $course->user_id = auth()->id();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }
}
