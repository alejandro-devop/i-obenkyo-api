<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\FrequencyType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()?: new User();
        $bills = $user->bills()->with('frequency')->get();
        return response()->json($bills);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['string'],
            'apply_date'    => ['required', 'date'],
            'frequency'     => ['required', 'numeric'],
            'value'         => ['required', 'numeric'],
            'apply_day'     => ['numeric'],
            'custom_days'   => ['string'],
            'is_open'       => ['boolean'],
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user()?: new User;
        $validator =  $this->validator($request->all());
        $fields = $validator->validate();
        $frequency = FrequencyType::findOrFail($fields['frequency']);
        $fields['frequency_id'] = $frequency->id;
        $saved = $user->bills()->save(new Bill($fields));
        $bill = Bill::with('frequency')->where('id', $saved->id)->first();
        return response()->json($bill);
    }

    public function update(Request $request, Bill $record)
    {
        if (($notOwned = $this->checkOwner($request, $record)) !== false) {
            return $notOwned;
        }
        $validator =  $this->validator($request->all());
        $fields = $validator->validate();
        $frequency = FrequencyType::findOrFail($fields['frequency']);
        $fields['frequency_id'] = $frequency->id;
        $record->update($fields);
        $record->load('frequency');
        return response()->json($record, 200);
    }

    public function destroy(Request $request, Bill $record)
    {
        if (($notOwned = $this->checkOwner($request, $record)) !== false) {
            return $notOwned;
        }
        $record->delete();
        return response()->json(null, 204);
    }
}
