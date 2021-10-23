<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagsResponse;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('tags')]
class TagController extends Controller
{
    #[Get('/')]
    public function list(): TagsResponse
    {
        return new TagsResponse(null);
    }
}
