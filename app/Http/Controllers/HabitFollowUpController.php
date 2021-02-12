<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\HabitFollowUp;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class HabitFollowUpController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'apply_date'    => ['required', 'date'],
            'story'         => ['string', 'nullable'],
            'counter'       => ['numeric'],
            'remove'        => ['boolean'],
            'update'        => ['boolean'],
            'accomplished'  => ['boolean'],
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/habits/follow-up/{habitId}",
     *      summary="Lists all follow ups on a habit",
     *      tags={"Habits follow up"},
     *      @OA\Response(
     *          response=200,
     *          description="List of follow ups",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Items(ref="#/components/schemas/HabitFollowUp")
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function followUpList(Habit $habit)
    {
        $followUps = $habit->getFollowUps();
        $followUpDays = [];
        foreach ($followUps as $followUp) {
            $date = Carbon::parse($followUp->apply_date);
            $followUpDays[] = [
                'id'            => $followUp->id,
                'date'          => $date->format('Y-m-d'),
                'completed'     => boolval($followUp->accomplished),
                'counter'       => $followUp->counter,
                'is_counter'    => $followUp->is_counter,
                'counter_goal'  => $followUp->counter_goal,
                'story'         => $followUp->story,
            ];
        }
        $response = [
            'title'     => $habit->title,
            'id'        => $habit->id,
            'is_counter'=> boolval($habit->is_counter),
            'daily_goal'=> $habit->counter_goal,
            'streak'    => $habit->streak_count,
            'goal'      => $habit->streak_goal,
            'max'       => $habit->max_streak,
            'stated'    => $habit->start,
            'ends'      => $habit->goal_date,
            'days'      => $followUpDays,
        ];
        return response()->json($response);
    }

    /**
     * @OA\Post(
     *      path="/api/habits/follow-up/{habitId}",
     *      summary="Allows to mark a follow up for the given day",
     *      tags={"Habits follow up"},
     *      security={{"bearer": {}}},
     *      @OA\Parameter(
     *           name="apply_date",
     *           required=true,
     *           in="query",
     *           description="Date to mark the habit as followed (YYYY-MM-DD)",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="story",
     *           in="query",
     *           description="A short description about the habit",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="counter",
     *           in="query",
     *           description="A value to mark the times completed an habit",
     *           @OA\Schema(
     *                  type="integer",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="remove",
     *           in="query",
     *           description="If the follow up should be removed",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="update",
     *           in="query",
     *           description="If the follow up should be updated",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="accomplished",
     *           in="query",
     *           description="If the follow up should be mark as completed",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="The updated or saved follow up",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/HabitFollowUp"
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function followUpMark(Request $request, Habit $habit)
    {
        $validator =  $this->validator($request->all());
        $fields = $validator->validate();
        $applyDate = Carbon::parse($fields['apply_date']);
        $dayFollowUp = HabitFollowUp::where('apply_date', $applyDate)->where('habit_id', $habit->id)->first();
        $isRemoving = isset($fields['remove']) && $fields['remove'] === true;
        $isUpdating = isset($fields['update']) && $fields['update'] === true;
        if (!is_null($dayFollowUp) && $isRemoving) {
            # If the follow up will be remove
            return $this->removeFollowUp($habit, $dayFollowUp);
        } else if (!is_null($dayFollowUp) && $isUpdating) {
            return $this->updateFollowUp($habit, $dayFollowUp, $fields);
        } else if (is_null($dayFollowUp)) {
            # If it's a new follow up
            return $this->createFollowUp($habit, $fields);
        } else {
            return response()->json(null, 204);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/habits/daily-follow-up/{dateStr}",
     *      summary="Lists the habits for a given day",
     *      tags={"Habits follow up"},
     *      security={{"bearer": {}}},
     *      @OA\Parameter(
     *           name="dateStr",
     *           in="path",
     *           description="The day to search for follow ups",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Follow ups for given name",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Items(ref="#/components/schemas/HabitFollowUp")
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function dailyFollowUp(Request $request, $dateStr)
    {
        $date = Carbon::parse($dateStr);
        $user = $request->user()?: new User;
        $followUps = HabitFollowUp::where(
            'apply_date',
            $date->format('Y-m-d'))
            ->with('habit')
            ->whereHas('habit', function ($q) use($user) {
                $q->where('user_id', $user->id);
            })
            ->get();
        return response()->json($followUps);
    }

    /**
     * Function to remove a follow up, it discounts from the user streak.
     */
    private function removeFollowUp (Habit $habit, HabitFollowUp $habitFollowUp)
    {
        if ($habitFollowUp->accomplished) {
            $counter = $habit->streak_count - 1;
            $habit->streak_count = $counter;
            $habit->save();
        }
        $habitFollowUp->delete();
        return response()->json(['removed' => true], 201);
    }

    /**
     * Function to encrease a habit streak counter
     */
    private function increaseHabitCounter (Habit &$habit) {
        $counter = $habit->streak_count + 1;
        $habit->streak_count = $counter;
        $habit->max_streak = $counter > $habit->max_streak? $counter : $habit->max_streak;
    }

    /**
     * Function to create a new follow up, it prepares everything for the follow up
     */
    private function createFollowUp(Habit $habit, $fields)
    {
        $isAccomplished = isset($fields['accomplished']) && $fields['accomplished'] === true;
        $isCounter = boolval($habit->is_counter);
        $isCounterComplete = isset($fields['counter']) && $fields['counter'] >= $habit->counter_goal;
        if ($isAccomplished || ($isCounter && $isCounterComplete)) {
            $this->increaseHabitCounter($habit);
        } else if (!$isCounter) {
            $habit->streak_count = 0;
        }
        $habit->save();
        $followUp = new HabitFollowUp($fields);
        $followUp->is_counter = $habit->is_counter;
        $followUp->counter_goal = $habit->counter_goal;
        $savedFollowUp = $habit->followUps()->save($followUp);
        return response()->json($savedFollowUp);
    }

    /**
     * Function to update a given follow up.
     */
    private function updateFollowUp(Habit $habit, HabitFollowUp $habitFollowUp, $fields)
    {
        $oldCounter = $habitFollowUp->counter;
        $habitFollowUp->counter = isset($fields['counter'])? $fields['counter'] : $habitFollowUp->counter;
        if (boolval($habit->is_counter) && $habitFollowUp->counter >= $habitFollowUp->counter_goal && !boolval($habitFollowUp->accomplished)) {
            $this->increaseHabitCounter($habit);
            $habitFollowUp->accomplished = true;
            $habitFollowUp->update();
        } else if (boolval($habit->is_counter) && $habitFollowUp->counter > $oldCounter) {
            $habitFollowUp->update($fields);
        } else if (!boolval($habit->is_counter)) {
            $habitFollowUp->update($fields);
        }
        $habit->save();
        return response()->json($habitFollowUp);
    }
}
