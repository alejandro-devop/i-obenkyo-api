<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(type="object")
 */
class Bill extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @OA\Property(property="id", type="integer", description="")
     * @OA\Property(property="apply_date", type="string", description="Date or day of month which the bill apply", default="YYYY-MM-DD HH:mm:ss") 
     * @OA\Property(property="custom_days", type="string", description="Days for the applied frequency", default="mon,tue,wed,thu,fri,sat,sun,*")
     * @OA\Property(property="description", type="string", description="A description for the bill") 
     * @OA\Property(property="is_open", type="boolean", description="If the system should keep generating this bill")
     * @OA\Property(property="frequency", ref="#/components/schemas/Frequency", description="")
     * @OA\Property(property="name", type="string", description="A simple title for the bill")
     * @OA\Property(property="value", type="number", description="Value/price, for the bill", default="0")
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
