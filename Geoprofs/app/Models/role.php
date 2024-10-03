<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    use HasFactory;

    protected $fillable = ['roleName']; // Role name must be fillable

    // Relationship with Users
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
