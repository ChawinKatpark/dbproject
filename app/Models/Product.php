<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image_url',
        'category_id',
    ];

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getUnitsSoldAttribute()
    {
        return $this->orderItems()
            ->whereHas('order', function ($query) {
                $query->where('status', '!=', 'canceled');
            })
            ->sum('quantity');
    }

    public function getRevenueAttribute()
    {
        return $this->orderItems()
            ->whereHas('order', function ($query) {
                $query->where('status', '!=', 'canceled');
            })
            ->sum(DB::raw('price * quantity'));
    }

    public function orderItems()
    {
        return $this->hasMany(\App\Models\OrderItem::class, 'product_id');
    }

    public function getImageUrlAttribute($value)
    {
        return $value ?: asset('/images/monkey.jpg');
    }
}
