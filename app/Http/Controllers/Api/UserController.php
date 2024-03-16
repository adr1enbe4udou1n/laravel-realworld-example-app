<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    /**
     * Register a new user.
     *
     * Register a new user
     */
    #[Post('users')]
    #[OA\Post(path: '/users', operationId: 'CreateUser', tags: ['User and Authentication'])]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: UserResource::class)
    )]
    // #[RequestBody(factory: NewUserRequestBody::class)]
    // #[Response(factory: ErrorValidationResponse::class, statusCode: 422)]
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
    #[OA\Post(path: '/users/login', operationId: 'Login', tags: ['User and Authentication'])]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: UserResource::class)
    )]
    // #[RequestBody(factory: LoginUserRequestBody::class)]
    // #[Response(factory: ErrorValidationResponse::class, statusCode: 422)]
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
    #[OA\Get(path: '/user', operationId: 'GetCurrentUser', tags: ['User and Authentication'], security: ['BearerToken'])]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: UserResource::class)
    )]
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
    #[OA\Put(path: '/user', operationId: 'UpdateCurrentUser', tags: ['User and Authentication'], security: ['BearerToken'])]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: UserResource::class)
    )]
    // #[RequestBody(factory: UpdateUserRequestBody::class)]
    // #[Response(factory: ErrorValidationResponse::class, statusCode: 422)]
    public function update(UpdateUserRequest $request): UserResource
    {
        $user = $request->updatedUser();
        $user->save();

        return new UserResource($user);
    }
}
