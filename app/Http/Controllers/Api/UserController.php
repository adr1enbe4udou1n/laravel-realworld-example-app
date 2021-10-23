<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\NewUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\OpenApi\RequestBodies\LoginUserRequestBody;
use App\OpenApi\RequestBodies\NewUserRequestBody;
use App\OpenApi\RequestBodies\UpdateUserRequestBody;
use App\OpenApi\Responses\ErrorValidationResponse;
use App\OpenApi\Responses\UserResponse;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;
use Vyuldashev\LaravelOpenApi\Attributes\Operation;
use Vyuldashev\LaravelOpenApi\Attributes\PathItem;
use Vyuldashev\LaravelOpenApi\Attributes\RequestBody;
use Vyuldashev\LaravelOpenApi\Attributes\Response;

#[PathItem]
class UserController extends Controller
{
    /**
     * Register a new user.
     *
     * Register a new user
     */
    #[Post('users')]
    #[Operation(tags: ['User and Authentication'])]
    #[RequestBody(factory: NewUserRequestBody::class)]
    #[Response(factory: UserResponse::class, statusCode: 200)]
    #[Response(factory: ErrorValidationResponse::class, statusCode: 422)]
    public function register(NewUserRequest $request): UserResource
    {
        return new UserResource(null);
    }

    /**
     * Existing user login.
     *
     * Login for existing user
     */
    #[Post('users/login')]
    #[Operation(tags: ['User and Authentication'])]
    #[RequestBody(factory: LoginUserRequestBody::class)]
    #[Response(factory: UserResponse::class, statusCode: 200)]
    #[Response(factory: ErrorValidationResponse::class, statusCode: 422)]
    public function login(LoginUserRequest $request): UserResource
    {
        return new UserResource(null);
    }

    /**
     * Get current user.
     *
     * Gets the currently logged-in user
     */
    #[Get('user', middleware: 'auth')]
    #[Operation(tags: ['User and Authentication'], security: 'BearerToken')]
    #[Response(factory: UserResponse::class, statusCode: 200)]
    public function current(): UserResource
    {
        return new UserResource(null);
    }

    /**
     * Update current user.
     *
     * Updated user information for current user
     */
    #[Put('user', middleware: 'auth')]
    #[Operation(tags: ['User and Authentication'], security: 'BearerToken')]
    #[RequestBody(factory: UpdateUserRequestBody::class)]
    #[Response(factory: UserResponse::class, statusCode: 200)]
    #[Response(factory: ErrorValidationResponse::class, statusCode: 422)]
    public function update(UpdateUserRequest $request): UserResource
    {
        return new UserResource(null);
    }
}
