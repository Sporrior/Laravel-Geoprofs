<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    protected $casts = [
        'start_datum' => 'datetime',
        'eind_datum' => 'datetime',
        'aanvraag_datum' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with the Type model.
     */
    public function type()
    {
        return $this->belongsTo(Type::class, 'verlof_soort');
    }

    /**
     * Relationship with the UserInfo model (instead of User).
     */
    public function user()
    {
        return $this->belongsTo(UserInfo::class, 'user_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

}
