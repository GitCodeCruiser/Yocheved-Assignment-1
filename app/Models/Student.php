<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'middle_name', 'last_name', 'date_of_birth'];
    protected $appends = ['full_name']; // Correct property name

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
    }

    public function studentAvailability(){
        return $this->hasMany(StudentAvailability::class, 'student_id', 'id');
    }
}
