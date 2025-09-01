<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name', 
        'phone_number', 
        'license_number', 
        'address',
        'date_of_birth',
        'joined_date'
    ];

    // Add this casting property
    protected $casts = [
        'date_of_birth' => 'date:Y-m-d',
        'joined_date' => 'date:Y-m-d',
    ];
}