<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @property User $resource
 */
class ProfileResource extends JsonResource
{
    public static $wrap = 'profile';

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
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
