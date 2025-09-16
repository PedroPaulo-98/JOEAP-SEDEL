<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportModality extends Model
{
    protected $guarded = ['id'];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class, 'enrollment_student');
    }
}
