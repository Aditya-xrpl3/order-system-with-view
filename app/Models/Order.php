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

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Table
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // Relasi ke OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
