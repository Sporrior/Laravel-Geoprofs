<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class team extends Model
{
    use HasFactory;

    protected $fillable = ['group_name'];

    /**
     * Relationship with the User model.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'team_id');
    }
}