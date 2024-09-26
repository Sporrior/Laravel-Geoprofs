<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class melding extends Model
{
    use HasFactory;

    public function bericht()
    {
        return $this->belongsTo(Berichten::class);
    }

    // A melding can have many managers
    public function managers()
    {
        return $this->hasMany(Manager::class);
    }

    // A melding can have many office management entries
    public function officeManagements()
    {
        return $this->hasMany(OfficeManagement::class);
    }
}
