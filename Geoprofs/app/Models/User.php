<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'failed_login_attempts',
        'bloack_attempts',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'password' => 'hashed',
        'blocked_until' => 'datetime',
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
     * Relationship with the UserInfo model.
     */
    public function info()
    {
        return $this->hasOne(UserInfo::class, 'id', 'id');
    }

    /**
     * Magic property access for UserInfo fields.
     *
     * If a property is not directly on the User model, attempt to retrieve it from UserInfo.
     */
    public function __get($key)
    {
        if (in_array($key, [
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
        ])) {
            return $this->info->$key ?? null;
        }

        return parent::__get($key);
    }

    /**
     * Check if the user is blocked.
     *
     * @return bool
     */
    public function isBlocked()
    {
        return $this->info && $this->info->blocked_until && now()->lessThan($this->info->blocked_until);
    }
}