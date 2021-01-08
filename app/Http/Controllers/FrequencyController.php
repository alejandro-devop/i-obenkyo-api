<?php

namespace App\Http\Controllers;

use App\Models\FrequencyType;
use App\Models\HabitCategory;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class FrequencyController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()?: new User;
        return response()->json($user->getFrequencies());
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'  => ['required', 'string', 'max:255'],
            'days'  => ['numeric'],
            'is_daily'  => ['boolean'],
            'is_weekly'  => ['boolean'],
            'is_monthly'  => ['boolean'],
            'is_every_year'  => ['boolean'],
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user()?: new User;
        $validator =  $this->validator($request->all());
        $fields = $validator->validate();
        $record = $user->frequencies()->save(new FrequencyType($fields));
        return response()->json($record);
    }

    public function update(Request $request, FrequencyType $record)
    {

        if (($notOwned = $this->checkOwner($request, $record)) !== false) {
            return $notOwned;
        }
        $validator =  $this->validator($request->all());
        $fields = $validator->validate();
        $record->update($fields);
        return response()->json($record, 200);
    }

    public function destroy(Request $request, FrequencyType $record)
    {
        if (($notOwned = $this->checkOwner($request, $record)) !== false) {
            return $notOwned;
        }
        // Todo: Validate associated habits
        $record->delete();
        return response()->json(null, 204);
    }
}
