<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_name',
        'address_full',
    ];
    /** Relationships */
    
    public function Mileages(): HasMany
    {
        return $this->hasMany(Mileage::class);
    }
}