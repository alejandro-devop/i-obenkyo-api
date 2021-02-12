<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(type="object")
 */
class Habit extends JsonResource
{
    /**
     * @OA\Property(property="id", type="integer", description="")
     * @OA\Property(property="title", type="string", description="")
     * @OA\Property(property="description", type="string", description="")
     * @OA\Property(property="start", type="string", description="Date where the habit should start", default="YYYY-MM-DD HH:mm:ss")
     * @OA\Property(property="goal_date", type="string", description="", default="YYYY-MM-DD HH:mm:ss")
     * @OA\Property(property="streak_count", type="integer", description="Current streak count")
     * @OA\Property(property="streak_goal", type="integer", description="Amount to be used as goal")
     * @OA\Property(property="category", ref="#/components/schemas/HabitCategory", description="Habit category")
     * @OA\Property(property="is_counter", type="boolean", description="If the habit should be accomplished by completing an amount of times")
     * @OA\Property(property="counter_goal", type="integer", description="Amount of times to complete the goal a single day")
     * @OA\Property(property="max_streak", type="integer", description="The user max streak in a row")
     * @OA\Property(property="should_keep", type="boolean", description="If the habit its something that benefits the user")
     * @OA\Property(property="should_avoid", type="boolean", description="If the habit its something that demages the user")
     * @OA\Property(property="followUps", type="array", @OA\Items(ref="#/components/schemas/HabitFollowUp"), description="")
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
