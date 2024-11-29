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
     * The user this information belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The role associated with this user info.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * The team associated with this user info.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}