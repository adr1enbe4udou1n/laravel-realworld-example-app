<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

/**
 * @property User $resource
 */
#[OA\Schema(
    schema: 'Profile',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'username',
            type: 'string'
        ),
        new OA\Property(
            property: 'bio',
            type: 'string'
        ),
        new OA\Property(
            property: 'image',
            type: 'string'
        ),
        new OA\Property(
            property: 'following',
            type: 'boolean',
            default: false
        ),
    ]
)]
#[OA\Schema(
    schema: 'ProfileResponse',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'profile',
            ref: '#/components/schemas/Profile'
        ),
    ]
)]
class ProfileResource extends JsonResource
{
    public static $wrap = 'profile';

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'username' => $this->resource->name,
            'bio' => $this->resource->bio,
            'image' => $this->resource->image,
            'following' => Auth::check() ? $this->resource->followers->contains('id', Auth::id()) : false,
        ];
    }
}
