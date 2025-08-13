<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'table_id',
        'total_price',
        'status',
    ];

    // Relasi ke OrderItem
    public function orderItems()
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function table()
    {
        return $this->belongsTo(\App\Models\Table::class);
    }
}
