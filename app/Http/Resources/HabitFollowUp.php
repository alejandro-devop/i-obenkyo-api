<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(type="object")
 */
class HabitFollowUp extends JsonResource
{
    /**
     *
     * @OA\Property(property="id", type="integer", description="")
     * @OA\Property(property="habit", ref="#/components/schemas/Habit", description="")
     * @OA\Property(property="apply_date", type="string", description="", default="YYYY-MM-DD HH:mm:ss")
     * @OA\Property(property="story", type="string", description="A description of what happened while accomplishing this habit")
     * @OA\Property(property="accomplished", type="boolean", description="")}
     * @OA\Property(property="counter", type="integer", description="Times completed by the user")
     * @OA\Property(property="counter_goal", type="integer", description="Times to mark the goal as accomplished")
     * @OA\Property(property="is_counter", type="boolean", description="If the follow up is completed by counting")
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
