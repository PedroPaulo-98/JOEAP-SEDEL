<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $guarded = ['id'];

    public function sportModality()
    {
        return $this->hasMany(SportModality::class);
    }
}
