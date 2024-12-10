<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $table = 'user_info';

    protected $fillable = [
        'voornaam',
        'tussennaam',
        'achternaam',
        'profielFoto',
        'email',
        'telefoon',
        'verlof_dagen',
        'role_id',
        'team_id',
    ];

    protected $casts = [
        'verlof_dagen' => 'integer',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->voornaam} {$this->tussennaam} {$this->achternaam}");
    }
}