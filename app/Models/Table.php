<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'number',
        'is_disabled',
        'is_reserved',
    ];

    // Relasi ke Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
