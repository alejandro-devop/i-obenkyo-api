<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiTestController extends Controller
{
    public function checkGet ()
    {
        return response()->json([
            'type' => 'get'
        ]);
    }

    public function checkPost ()
    {
        return response()->json([
            'type' => 'post'
        ]);
    }

    public function checkPut ()
    {
        return response()->json([
            'type' => 'put'
        ]);
    }

    public function checkPatch ()
    {
        return response()->json([
            'type' => 'patch'
        ]);
    }

    public function checkDelete ()
    {
        return response()->json([
            'type' => 'delete'
        ]);
    }
}
