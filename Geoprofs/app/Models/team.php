<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class team extends Model
{
    use HasFactory;

    protected $fillable = ['group_name']; // Make sure group_name is fillable


    public function users()
    {
        return $this->hasMany(User::class);
    }
}
