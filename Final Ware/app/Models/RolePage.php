<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolePage extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'page_name'];

    // 👇 Define the relationship with Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
