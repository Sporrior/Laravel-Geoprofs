<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class verlofaanvragen extends Model
{
    use HasFactory;

    protected $table = 'verlofaanvragen';

    protected $fillable = [
        'verlof_reden',
        'aanvraag_datum',
        'start_datum',
        'eind_datum',
        'status',
        'verlof_soort',
        'user_id',
        'weigerreden',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'verlof_soort');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}