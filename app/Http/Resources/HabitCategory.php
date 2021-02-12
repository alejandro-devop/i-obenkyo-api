<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(type="object")
 */
class HabitCategory extends JsonResource
{
    /**
     * @OA\Property(property="id", type="integer", description="")
     * @OA\Property(property="name", type="string", description="")
     * @OA\Property(property="icon", type="string", description="")
     * @OA\Property(property="created_at", type="string", default="YYYY-MM-DD HH:mm:ss", description="Created date")
     * @OA\Property(property="updated_at", type="string", default="YYYY-MM-DD HH:mm:ss", description="Updated date")
     * Transform the resource into an array.
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
