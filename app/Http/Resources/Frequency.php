<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(type="object")
 */
class Frequency extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @OA\Property(property="id", type="integer", description="")
     * @OA\Property(property="name", type="string", description="")
     * @OA\Property(property="days", type="integer", description="Amount of days for the frequency")
     * @OA\Property(property="is_daily", type="boolean", description="If apply daily")
     * @OA\Property(property="is_weekly", type="boolean", description="If apply weekly")
     * @OA\Property(property="is_monthly", type="boolean", description="If apply monthly")
     * @OA\Property(property="is_every_year", type="boolean", description="If apply every year")
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
