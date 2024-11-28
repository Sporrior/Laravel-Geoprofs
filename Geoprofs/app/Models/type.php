<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class type extends Model
{
    use HasFactory;

    protected $fillable = ['type']; 

    public function verlofaanvragen()
    {
        return $this->hasMany(Verlofaanvragen::class, 'verlof_soort');
    }
}
