<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentStudent extends Model
{
    protected $guarded = ['id'];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
