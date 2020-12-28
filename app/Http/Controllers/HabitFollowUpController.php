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
            'remove'        => ['boolean'],
            'accomplished'  => ['boolean'],
        ]);
    }

    public function followUpList(Habit $habit)
    {
        return $habit->getFollowUps();
    }

    public function followUpMark(Request $request, Habit $habit)
    {
        $validator =  $this->validator($request->all());
        $fields = $validator->validate();
        $applyDate = Carbon::parse($fields['apply_date']);
        $dayFollowUp = HabitFollowUp::where('apply_date', $applyDate)->first();

        if (!is_null($dayFollowUp) && isset($fields['remove']) && $fields['remove'] === true) {
            # If the follow up will be remove
            if ($dayFollowUp->accomplished) {
                $counter = $habit->streak_count - 1;
                $habit->streak_count = $counter;
                $habit->save();
            }
            $dayFollowUp->delete();
            return response()->json(['removed' => true], 201);
        } else if (is_null($dayFollowUp)) {
            # If it's a new follow up
            if (isset($fields['accomplished']) && $fields['accomplished'] === true) {
                $counter = $habit->streak_count + 1;
                $habit->streak_count = $counter;
                $habit->max_streak = $counter > $habit->max_streak? $counter : $habit->max_streak;
            } else {
                $habit->streak_count = 0;
            }
            $habit->save();
            $followUp = $habit->followUps()->save(new HabitFollowUp($fields));
            return response()->json($followUp);
        } else {
            return response()->json(null, 204);
        }
    }
}
