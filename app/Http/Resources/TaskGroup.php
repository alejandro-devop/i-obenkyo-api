<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(type="object")
 */
class TaskGroup extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @OA\Property(property="id", type="integer", description="")
     * @OA\Property(property="name", type="string", description="")
     * @OA\Property(property="user", ref="#/components/schemas/User", description="")
     * @OA\Property(property="tasks", type="array", @OA\Items(ref="#/components/schemas/Task"), description="")
     * @OA\Property(property="created_at", type="string", default="YYYY-MM-DD HH:mm:ss", description="Created date")
     * @OA\Property(property="updated_at", type="string", default="YYYY-MM-DD HH:mm:ss", description="Updated date")
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
