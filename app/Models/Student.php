<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = ['id'];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
