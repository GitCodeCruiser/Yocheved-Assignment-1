<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['start_date', 'start_time', 'end_time', 'target', 'is_daily', 'status', 'notified_at'];
    protected $appends = ['status_column', 'daily'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_schedules', 'session_id', 'student_id');
    }

    public function getStatusColumnAttribute()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 1 ? 'completed' : 'pending';
    }

    public function getDailyAttribute()
    {
        return $this->attributes['is_daily'] == 1 ? 'Yes' : 'No';
    }

    public function rating(){
        return $this->hasOne(SessionRating::class, 'session_id', 'id')->latestOfMany();
    }
}
