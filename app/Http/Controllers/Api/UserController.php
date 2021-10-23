<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\NewUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResponse;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;

class UserController extends Controller
{
    #[Post('users/login')]
    public function register(NewUserRequest $request): UserResponse
    {
        return new UserResponse(null);
    }

    #[Post('users')]
    public function login(LoginUserRequest $request): UserResponse
    {
        return new UserResponse(null);
    }

    #[Get('user', middleware: 'auth')]
    public function current(): UserResponse
    {
        return new UserResponse(null);
    }

    #[Put('user', middleware: 'auth')]
    public function update(UpdateUserRequest $request): UserResponse
    {
        return new UserResponse(null);
    }
}
