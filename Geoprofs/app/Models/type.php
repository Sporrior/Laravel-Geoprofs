<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class type extends Model
{
    use HasFactory;

    protected $fillable = ['type']; // Fillable for type name

    // Relationship with Leave Requests
    public function verlofaanvragens()
    {
        return $this->hasMany(Verlofaanvragen::class, 'verlof_soort');
    }
}
