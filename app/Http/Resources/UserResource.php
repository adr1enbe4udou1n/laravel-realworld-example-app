<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Support\Jwt;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @property User $resource
 */
#[OA\Schema(
    schema: 'User',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'username',
            type: 'string'
        ),
        new OA\Property(
            property: 'email',
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
            property: 'token',
            type: 'string'
        ),
    ]
)]
#[OA\Schema(
    schema: 'UserResponse',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'user',
            ref: '#/components/schemas/User'
        ),
    ]
)]
class UserResource extends JsonResource
{
    public static $wrap = 'user';

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
            'email' => $this->resource->email,
            'bio' => $this->resource->bio,
            'image' => $this->resource->image,
            'token' => app(Jwt::class)->generate($this->resource),
        ];
    }
}
