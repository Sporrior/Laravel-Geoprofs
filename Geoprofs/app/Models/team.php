<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class team extends Model
{
    use HasFactory;

    // A team can have many werknemers
    public function werknemers()
    {
        return $this->hasMany(Werknemer::class);
    }

    // A team can have many managers
    public function managers()
    {
        return $this->hasMany(Manager::class);
    }
}
