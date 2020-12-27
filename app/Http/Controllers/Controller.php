<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function checkOwner(Request $request, $object)
    {
        $user = $request->user()?: new User;
        $objectUser = $object->user()->first()?: new User;
        if ($user->id !== $objectUser->id) {
            return response()->json(['message' => 'Not allowed', 'errors' => ['owner' => 'You are not the object owner']], 403);
        }
        return false;
    }
}
