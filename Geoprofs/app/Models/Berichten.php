<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berichten extends Model
{
    use HasFactory;

        // A bericht can have many meldings
        public function meldings()
        {
            return $this->hasMany(Melding::class);
        }

        // A bericht can have many managers
        public function managers()
        {
            return $this->hasMany(Manager::class);
        }

        // A bericht can have many office management entries
        public function officeManagements()
        {
            return $this->hasMany(OfficeManagement::class);
        }
}
