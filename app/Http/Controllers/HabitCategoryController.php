<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HabitCategory;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class HabitCategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()?: new User;
        return response()->json($user->habitCategories()->get());
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'  => ['required', 'string', 'max:255'],
            'icon'  => ['string', 'max:100']
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user()?: new User;
        $fields = $request->only('name', 'icon');
        $this->validator($request->all())->validate();
        $category = $user->habitCategories()->save(new HabitCategory($fields));
        return response()->json($category);
    }

    public function update(Request $request, HabitCategory $habitCategory)
    {
        $fields = $request->only('name', 'icon');
        if (($notOwned = $this->checkOwner($request, $habitCategory)) !== false) {
            return $notOwned;
        }
        $this->validator($request->all())->validate();
        $habitCategory->update($fields);
        return response()->json($habitCategory, 200);
    }

    public function destroy(Request $request, HabitCategory $habitCategory)
    {
        if (($notOwned = $this->checkOwner($request, $habitCategory)) !== false) {
            return $notOwned;
        }
        // Todo: Validate associated habits
        $habitCategory->delete();
        return response()->json(null, 204);
    }
}
