<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $guarded = ['id'];

    public function student()
    {
        return $this->hasMany(Student::class);
    }

    public function technical()
    {
        return $this->hasMany(Technical::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
