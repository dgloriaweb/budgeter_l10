<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserPartner extends Model
{
    use HasFactory;
    
    // Explicitly define the table name
    protected $table = 'user_partner';

    protected $fillable = [
        'user_id',
        'partner_id',
        'enabled'
    ];

   
}
