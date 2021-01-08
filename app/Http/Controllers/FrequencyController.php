<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FrequencyController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()?: new User;
        return response()->json($user->getFrequencies());
    }
}
