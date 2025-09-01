<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'product_name',
        'pid',
        'quantity_returned',
        'reason',
        'returned_by',
        'status'
    ];

    // Relationship to user
    public function staff()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'pid', 'pid');
    }
}