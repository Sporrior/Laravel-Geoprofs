<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

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
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Override save to ensure `password` is handled properly in the `users` table.
     */
    public function save(array $options = [])
    {
        $isCreating = !$this->exists;

        if ($isCreating && !isset($this->attributes['password'])) {
            throw new \Exception("Password is required for new User records.");
        }

        if (isset($this->attributes['password'])) {
            $password = $this->attributes['password'];
            unset($this->attributes['password']);

            parent::save($options);

            DB::table($this->getTable())->updateOrInsert(
                ['id' => $this->id],
                ['password' => $password, 'created_at' => now(), 'updated_at' => now()]
            );

            return true;
        }

        return parent::save($options);
    }

    /**
     * Relationship with the UserInfo model.
     */
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, 'id', 'id');
    }

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
}