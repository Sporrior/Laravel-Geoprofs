<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'voornaam',
        'tussennaam',
        'achternaam',
        'profielFoto',
        'telefoon',
        'email',
        'password',
        'verlof_dagen',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationship with Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relationship with Team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Relationship with Leave Requests
    public function verlofaanvragens()
    {
        return $this->hasMany(Verlofaanvragen::class);
    }
}
