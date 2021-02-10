<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(type="object")
 */
class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @OA\Property(property="id", type="integer")
     * @OA\Property(property="name", type="string")
     * @OA\Property(property="email", type="string")
     * @OA\Property(property="email_verified_at", type="string", default="YYYY-MM-DD HH:mm:ss")
     * @OA\Property(property="created_at", type="string", default="YYYY-MM-DD HH:mm:ss")
     * @OA\Property(property="updated_at", type="string", default="YYYY-MM-DD HH:mm:ss")
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
