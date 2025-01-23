<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    use HasFactory;

    protected $fillable = ['role_name'];

    /**
     * Relationship with the User model.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}