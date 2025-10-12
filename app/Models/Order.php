<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipping_address',
        'total_amount',
        'status',
        'payment_time',
        'payment_slip_path',
        'payment_status',
    ];

    // Relationship to order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}