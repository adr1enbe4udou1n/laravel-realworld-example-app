<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResponse;
use App\Models\User;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('profiles/celeb_{username}')]
class ProfileController extends Controller
{
    #[Get('/')]
    public function get(User $user): ProfileResponse
    {
        return new ProfileResponse($user);
    }

    #[Post('follow', middleware: 'auth')]
    public function follow(User $user): ProfileResponse
    {
        return new ProfileResponse($user);
    }

    #[Delete('follow', middleware: 'auth')]
    public function unfollow(User $user): ProfileResponse
    {
        return new ProfileResponse(null);
    }
}
