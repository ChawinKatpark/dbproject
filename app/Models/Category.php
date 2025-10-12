<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_url',

    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // app/Models/Category.php

    public function getImageUrlAttribute($value)
    {
        return $value ?: asset('/images/monkey.jpg');
    }

}
