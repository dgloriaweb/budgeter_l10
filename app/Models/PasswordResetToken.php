<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    use HasFactory;


    // Make sure mass assignment is enabled for these fields
    protected $fillable = ['email', 'token', 'created_at'];
}
