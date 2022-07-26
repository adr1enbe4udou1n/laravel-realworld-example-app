<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\OpenApi\Responses\ProfileResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Vyuldashev\LaravelOpenApi\Attributes\Operation;
use Vyuldashev\LaravelOpenApi\Attributes\PathItem;
use Vyuldashev\LaravelOpenApi\Attributes\Response;

#[Prefix('profiles/celeb_{username}')]
#[PathItem]
class ProfileController extends Controller
{
    /**
     * Get a profile.
     *
     * Get a profile of a user of the system. Auth is optional
     *
     * @param  User  $username Username of the profile to get
     */
    #[Get('/')]
    #[Operation('GetProfileByUsername', tags: ['Profile'])]
    #[Response(factory: ProfileResponse::class, statusCode: 200)]
    public function get(User $username): ProfileResource
    {
        return new ProfileResource($username);
    }

    /**
     * Follow a user.
     *
     * Follow a user by username
     *
     * @param  User  $username Username of the profile you want to follow
     */
    #[Post('follow', middleware: 'auth')]
    #[Operation('FollowUserByUsername', tags: ['Profile'], security: 'BearerToken')]
    #[Response(factory: ProfileResponse::class, statusCode: 200)]
    public function follow(User $username): ProfileResource
    {
        $username->followers()->attach(Auth::id());

        return new ProfileResource($username);
    }

    /**
     * Unfollow a user.
     *
     * Unfollow a user by username
     *
     * @param  User  $username Username of the profile you want to unfollow
     */
    #[Delete('follow', middleware: 'auth')]
    #[Operation('UnfollowUserByUsername', tags: ['Profile'], security: 'BearerToken')]
    #[Response(factory: ProfileResponse::class, statusCode: 200)]
    public function unfollow(User $username): ProfileResource
    {
        $username->followers()->detach(Auth::id());

        return new ProfileResource($username);
    }
}
