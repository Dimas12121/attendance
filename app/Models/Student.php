<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name', 'student_id', 'phone_parent', 'birth_date', 'face_descriptor'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
