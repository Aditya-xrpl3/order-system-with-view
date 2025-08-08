<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'image_url'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer'
    ];

    // Scope untuk produk tersedia
    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Scope untuk stok rendah
    public function scopeLowStock($query, $threshold = 5)
    {
        return $query->where('stock', '<=', $threshold)->where('stock', '>', 0);
    }

    // Relasi ke OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
