<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('profiles/{username}')]
class ProfileController extends Controller
{
    /**
     * Get a profile.
     *
     * Get a profile of a user of the system. Auth is optional
     *
     * @param  User  $username  Username of the profile to get
     */
    #[Get('/')]
    #[OA\Get(path: '/profiles/{username}', operationId: 'GetProfileByUsername', tags: ['Profile'])]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: ProfileResource::class)
    )]
    public function get(
        #[OA\PathParameter(
            schema: new OA\Schema(type: 'string'),
        )] User $username
    ): ProfileResource {
        return new ProfileResource($username);
    }

    /**
     * Follow a user.
     *
     * Follow a user by username
     *
     * @param  User  $username  Username of the profile you want to follow
     */
    #[Post('follow', middleware: 'auth')]
    #[OA\Post(path: '/profiles/{username}/follow', operationId: 'FollowUserByUsername', tags: ['Profile'], security: ['BearerToken'])]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: ProfileResource::class)
    )]
    public function follow(
        #[OA\PathParameter(
            schema: new OA\Schema(type: 'string'),
        )] User $username
    ): ProfileResource {
        $username->followers()->attach(Auth::id());

        return new ProfileResource($username);
    }

    /**
     * Unfollow a user.
     *
     * Unfollow a user by username
     *
     * @param  User  $username  Username of the profile you want to unfollow
     */
    #[Delete('follow', middleware: 'auth')]
    #[OA\Delete(path: '/profiles/{username}/follow', operationId: 'UnfollowUserByUsername', tags: ['Profile'], security: ['BearerToken'])]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: ProfileResource::class)
    )]
    public function unfollow(
        #[OA\PathParameter(
            schema: new OA\Schema(type: 'string'),
        )] User $username
    ): ProfileResource {
        $username->followers()->detach(Auth::id());

        return new ProfileResource($username);
    }
}
