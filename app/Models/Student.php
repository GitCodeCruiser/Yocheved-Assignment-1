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

    // Define fillable attributes for mass assignment
    protected $fillable = ['email', 'first_name', 'middle_name', 'last_name', 'date_of_birth', 'slug'];

    // Define appended attributes that are dynamically added to the model
    protected $appends = ['full_name'];

    // Generate the full name of the student by concatenating first, middle, and last names
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
    }

    // Define the relationship between a student and their availability
    public function studentAvailability()
    {
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
            ->generateSlugsFrom(['first_name', 'middle_name', 'last_name']) // Generate slug from these fields
            ->saveSlugsTo('slug'); // Save the generated slug to the 'slug' field
    }
}
