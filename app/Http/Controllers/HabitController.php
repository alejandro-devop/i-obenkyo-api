<?php

namespace App\Http\Controllers;

use App\Models\HabitCategory;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class HabitController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()?: new User;
        $records = $user->getHabits();
        return response()->json($records);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['string'],
            'is_counter'    => ['boolean'],
            'counter_goal'  => ['numeric'],
            'start'         => ['required', 'date'],
            'should_keep'   => ['boolean'],
            'should_avoid'  => ['boolean'],
            'goal_date'     => ['required', 'date', 'after:start_date'],
            'streak_goal'   => ['required', 'numeric',  'gte:7'],
            'category'      => ['required', 'numeric'],
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user()?: new User;
        $validator =  $this->validator($request->all());
        $fields = $validator->validate();
        $category = HabitCategory::findOrFail($fields['category']);
        $fields['category_id'] = $category->id;
        $fields['streak_count'] = 0;
        $habitSaved = $user->habits()->save(new Habit($fields));
        $habit = Habit::with('category')->with('followUps')->where('id', $habitSaved->id)->first();
        return response()->json($habit);
    }

    public function update(Request $request, Habit $habit)
    {
        if (($notOwned = $this->checkOwner($request, $habit)) !== false) {
            return $notOwned;
        }
        $fields = $this->validator($request->all())->validate();
        $category = HabitCategory::findOrFail($fields['category']);
        $fields['category_id'] = $category->id;
        $habit->update($fields);
        return response()->json($habit, 200);
    }

    public function destroy(Request $request, Habit $habit)
    {
        if (($notOwned = $this->checkOwner($request, $habit)) !== false) {
            return $notOwned;
        }
        // Todo: Validate associated habits
        $habit->delete();
        return response()->json(null, 204);
    }
}
