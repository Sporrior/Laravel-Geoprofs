<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class status extends Model
{
    use HasFactory;

    protected $table = 'statuses';

    // The fields that can be mass-assigned
    protected $fillable = ['status'];

    /**
     * Relationship to Verlofaanvragen model.
     *
     * A Status can have many Verlofaanvragen.
     */
    public function verlofaanvragen()
    {
        return $this->hasMany(Verlofaanvragen::class, 'verlof_soort');
    }
}
