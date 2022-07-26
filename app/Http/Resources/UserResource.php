<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Support\Jwt;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class UserResource extends JsonResource
{
    public static $wrap = 'user';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
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
