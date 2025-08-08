<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'order_id',
        'file_path',
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
