<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logboek extends Model
{
    use HasFactory;

    protected $table = 'logboeken'; // Table name matches the migration

    protected $fillable = [
        'user_id',
        'actie',
        'actie_beschrijving',
        'actie_datum',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}