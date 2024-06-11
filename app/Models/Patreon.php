<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patreon extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'email',
        'pledge_created'
    ];
}
