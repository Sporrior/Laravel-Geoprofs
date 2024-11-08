<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    use HasFactory;

    protected $fillable = ['rolename'];

    // Relationship with Users
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
