<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $guarded = ['id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function sportModality()
    {
        return $this->belongsTo(SportModality::class);
    }

    public function enrollmentStudents()
    {
        return $this->hasMany(EnrollmentStudent::class);
    }
}
