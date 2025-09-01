<?php

namespace App\Models; // Ensure this namespace exists

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = ['vehicle_type', 'model_year', 'plate_number'];

      public function driver()
    {
        return $this->belongsTo(Driver::class);
        // If your foreign key column name is different, specify it:
        // return $this->belongsTo(Driver::class, 'driver_id');
    }
}