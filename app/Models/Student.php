<?php

namespace App\Models;

use App\Models\StudentAvailability;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


class Student extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['email', 'first_name', 'middle_name', 'last_name', 'date_of_birth', 'slug'];
    protected $appends = ['full_name']; // Correct property name

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
    }

    public function studentAvailability(){
        return $this->hasMany(StudentAvailability::class, 'student_id', 'id');
    }

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['first_name', 'middle_name', 'last_name'])
            ->saveSlugsTo('slug');
    }
}
