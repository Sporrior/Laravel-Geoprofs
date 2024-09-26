<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class manager extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A manager belongs to a team (nullable)
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // A manager belongs to a melding (nullable)
    public function melding()
    {
        return $this->belongsTo(Melding::class);
    }

    // A manager belongs to a bericht (nullable)
    public function bericht()
    {
        return $this->belongsTo(Berichten::class);
    }
}
