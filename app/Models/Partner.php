<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner',
    ];
    /** Relationships */
    
    
    public function Mileages(): HasMany
    {
        return $this->hasMany(Mileage::class);
    }
    
    public function Users()
    {
        return $this->belongsToMany(User::class, 'user_partner');
    }
}
