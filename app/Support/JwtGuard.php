<?php

namespace App\Support;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JwtGuard implements Guard
{
    use GuardHelpers;

    private Request $request;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $user = null;

        $token = explode(' ', $this->request->header('Authorization'))[1] ?? null;

        if (! empty($token)) {
            $plain = app(Jwt::class)->parse($token);

            $id = $plain->claims()->get('uid');

            $user = $this->provider->retrieveById($id);
        }

        return $this->user = $user;
    }

    public function validate(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        return $user && $this->provider->validateCredentials(
            $user,
            $credentials
        );
    }

    public function attempt(array $credentials = [])
    {
        if ($this->validate($credentials)) {
            $this->user = $this->provider->retrieveByCredentials($credentials);

            return true;
        }

        return false;
    }
}
