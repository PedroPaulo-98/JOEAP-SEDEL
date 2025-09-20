<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PageText extends Model
{
    use HasFactory;
    
    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable = [
        'data',
        'updated_by',
    ];
    
    public function getText($key, $default = null)
    {
        if ($key === 'banner') {
            return isset($this->data['banner']) ? asset('storage/'.$this->data['banner']) : asset('img/banner.png');
        }
        if ($key === 'middle_banner') {
            return isset($this->data['middle_banner']) ? asset('storage/'.$this->data['middle_banner']) : asset('img/middle_banner.png');
        }
        
        return $this->data[$key] ?? $default;
    }
}