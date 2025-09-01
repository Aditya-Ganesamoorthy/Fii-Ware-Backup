<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases'; // Optional, only if your table is named differently

    protected $fillable = [
        'purchase_id',
        'vendor_id',
        'product_id',
        'product_name',
        'quantity',
        'status',
        'purchase_date',
    ];

    // Vendor Relationship
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Product Relationship
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Optional: Format purchase_date as Carbon instance
    protected $casts = [
        'purchase_date' => 'datetime',
    ];
}
