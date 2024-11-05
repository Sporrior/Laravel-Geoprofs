<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class logboek extends Model
{
    protected $table = 'logboeken';
    use HasFactory;
    protected $fillable = [
        'user_id',
        'actie',
        'actie_beschrijving',
        'actie_datum',
    ];
    public function user()
    {
     return $thin->belongsTo(User::class, 'user_id');
    }
}
