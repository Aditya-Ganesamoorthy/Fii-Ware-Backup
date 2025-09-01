<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'pid',
        'name',
        'company',
        'style',
        'color',
        'size',
        'qty',
        'sku',
        'category_id', // ✅ Ensure this is included
        'image',
    ];

    /**
     * Get the category associated with the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
