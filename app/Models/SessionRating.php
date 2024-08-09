<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'student_id',
        'total_rating',
        'obtained_rating'
    ];

    public function session(){
        return $this->belongsTo(Session::class, 'id', 'session_id');
    }
}
