<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class verlofaanvragen extends Model
{
    use HasFactory;

    protected $table = 'verlofaanvragens';

    public function status()
    {
        return $this->belongsTo(Status::class, 'verlof_soort');
    }

    /**
     * Relationship to the User model.
     *
     * A Verlofaanvragen belongs to one user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
