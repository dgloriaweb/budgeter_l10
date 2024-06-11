<?php

namespace App\Services;

use App\Models\User;
use Exception;

class UserService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
 
}
