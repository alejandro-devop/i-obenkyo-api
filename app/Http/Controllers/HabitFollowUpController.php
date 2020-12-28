<?php

namespace App\Http\Controllers;

use App\Models\Habit;
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
            'story'         => ['string'],
            'counter'       => ['numeric'],
            'remove'        => ['boolean'],
            'update'        => ['boolean'],
            'accomplished'  => ['boolean'],
        ]);
    }

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
            'streak'    => $habit->streak_count,
            'goal'      => $habit->streak_goal,
            'max'       => $habit->max_streak,
            'stated'    => $habit->start,
            'ends'      => $habit->goal_date,
            'days'      => $followUpDays,
        ];
        return response()->json($response);
    }

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

    private function increaseHabitCounter (Habit &$habit) {
        $counter = $habit->streak_count + 1;
        $habit->streak_count = $counter;
        $habit->max_streak = $counter > $habit->max_streak? $counter : $habit->max_streak;
    }

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
        $habit->followUps()->save($followUp);
        return response()->json($followUp);
    }

    private function updateFollowUp(Habit $habit, HabitFollowUp $habitFollowUp, $fields)
    {
        $habitFollowUp->counter = isset($fields['counter'])? $fields['counter'] : $habitFollowUp->counter;
        if ($habitFollowUp->counter >= $habitFollowUp->counter_goal && !boolval($habitFollowUp->accomplished)) {
            $this->increaseHabitCounter($habit);
            $habitFollowUp->accomplished = true;
        }
        $habit->save();
        $habitFollowUp->update();
        return response()->json($habitFollowUp);
    }
}
