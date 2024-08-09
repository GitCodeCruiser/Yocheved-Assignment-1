<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['start_date_time', 'end_date_time', 'target', 'is_daily'];

    public function student(){
        return $this->hasMany(Student::class, 'student_id', 'id');
    }
}
