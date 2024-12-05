<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends UserInfo
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
            // Extract and save password separately
            $password = $this->attributes['password'];
            unset($this->attributes['password']);

            // Save the parent data (`user_info` table)
            parent::save($options);

            // Ensure the password is stored in the `users` table
            DB::table($this->getTable())->updateOrInsert(
                ['id' => $this->id],
                ['password' => $password, 'created_at' => now(), 'updated_at' => now()]
            );
            return true;
        }

        // Default behavior for updates
        return parent::save($options);
    }
}