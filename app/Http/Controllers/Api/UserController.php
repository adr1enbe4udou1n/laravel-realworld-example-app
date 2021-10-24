<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\OpenApi\RequestBodies\LoginUserRequestBody;
use App\OpenApi\RequestBodies\NewUserRequestBody;
use App\OpenApi\RequestBodies\UpdateUserRequestBody;
use App\OpenApi\Responses\ErrorValidationResponse;
use App\OpenApi\Responses\UserResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
        $user = $request->newUser();
        $user->save();

        return new UserResource($user);
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
    public function login(Request $request): UserResource
    {
        if (! Auth::attempt($request->user)) {
            throw new BadRequestHttpException('Bad credentials');
        }

        return new UserResource(Auth::user());
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
        return new UserResource(Auth::user());
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
        $user = $request->updatedUser();
        $user->save();

        return new UserResource($user);
    }
}
