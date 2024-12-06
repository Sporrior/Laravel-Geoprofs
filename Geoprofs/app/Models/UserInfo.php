<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_info';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'voornaam',
        'tussennaam',
        'achternaam',
        'profielFoto',
        'email',
        'telefoon',
        'verlof_dagen',
        'failed_login_attempts',
        'blocked_until',
        'role_id',
        'team_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'blocked_until' => 'datetime',
        'failed_login_attempts' => 'integer',
        'verlof_dagen' => 'integer',
    ];

    /**
     * Relationship with the Role model.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Relationship with the Team model.
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->voornaam} {$this->tussennaam} {$this->achternaam}");
    }
}