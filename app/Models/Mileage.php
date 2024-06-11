<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mileage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'opening_mileage',
        'closing_mileage',
        'partner_id',
        'location_id_start',
        'location_id_end',
        'is_personal',
        'comments',
    ];

    /** Relationships */
    /* mileage data belongs to one user */
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /* mileage data belongs to one partner */
    public function Partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
    /* mileage data belongs to one location */
    public function LocationStart(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id_start','id');
    }
    /* mileage data belongs to one location */
    public function LocationEnd(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id_end', 'id');
    }
}
