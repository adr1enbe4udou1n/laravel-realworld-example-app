<?php

namespace App\Support;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

class JwtGuard implements Guard
{
    private UserProvider $userProvider;
    private Authenticatable $user;

    public function __construct(UserProvider $user)
    {
        $this->userProvider = $user;
    }

    public function check()
    {
    }

    public function guest()
    {
    }

    public function user()
    {
        return $this->user;
    }

    public function id()
    {
        return $this->user->getAuthIdentifier();
    }

    public function validate(array $credentials = [])
    {
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }
}
