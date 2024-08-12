<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    // Define fillable attributes for mass assignment
    protected $fillable = ['start_date', 'start_time', 'end_time', 'target', 'is_daily', 'status', 'notified_at'];

    // Define appended attributes that are dynamically added to the model
    protected $appends = ['status_column', 'daily'];

    // Define the relationship between sessions and students
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_schedules', 'session_id', 'student_id');
    }

    // Get a custom attribute 'status_column' to show if the session is completed or pending
    public function getStatusColumnAttribute()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 1 ? 'completed' : 'pending';
    }

    // Get a custom attribute 'daily' to show if the session is daily or not
    public function getDailyAttribute()
    {
        return $this->attributes['is_daily'] == 1 ? 'Yes' : 'No';
    }

    // Define the relationship between a session and its rating, fetching the latest rating if there are multiple
    public function rating()
    {
        return $this->hasOne(SessionRating::class, 'session_id', 'id')->latestOfMany();
    }
}
