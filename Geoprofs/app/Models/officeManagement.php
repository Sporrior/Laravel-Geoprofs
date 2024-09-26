<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class officeManagement extends Model
{
    use HasFactory;

        // OfficeManagement belongs to a user
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        // OfficeManagement belongs to a melding (nullable)
        public function melding()
        {
            return $this->belongsTo(Melding::class);
        }

        // OfficeManagement belongs to a bericht (nullable)
        public function bericht()
        {
            return $this->belongsTo(Berichten::class);
        }
}
