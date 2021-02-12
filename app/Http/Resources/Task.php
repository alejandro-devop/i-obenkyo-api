<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(type="object")
 */
class Task extends JsonResource
{
    /**
     * @OA\Property(property="id", type="integer", description="")
     * @OA\Property(property="text", type="string", description="")
     * @OA\Property(property="description", type="string", description="")
     * @OA\Property(property="is_done", type="boolean", description="")
     * @OA\Property(property="apply_date", type="string", description="")
     * @OA\Property(property="is_all_day", type="boolean", description="")
     * @OA\Property(property="group", ref="#/components/schemas/TaskGroup", description="")
     * @OA\Property(property="created_at", type="string", default="YYYY-MM-DD HH:mm:ss", description="Created date")
     * @OA\Property(property="updated_at", type="string", default="YYYY-MM-DD HH:mm:ss", description="Updated date")
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
