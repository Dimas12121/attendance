<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['student_id', 'type', 'photo', 'logged_at'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
