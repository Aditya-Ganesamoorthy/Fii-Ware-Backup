<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'vendor_id',
         'pid',
        'name',
        'quantity',
        'created_at',

    'sales_date', 
    ];

    /**
     * Get the vendor associated with the sale.
     */
    // app/Models/Sale.php


public function product()
{
    return $this->belongsTo(Product::class, 'pid', 'pid');
}
public function vendor()
{
    return $this->belongsTo(Vendor::class, 'vendor_id');
}


}
    