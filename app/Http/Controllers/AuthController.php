<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|string|email',
            'password'  => 'required|string',
        ]);

        $fields = $request->only('email', 'password');

        if (!Auth::attempt($fields)) {
            return response()->json([
                'logged'    => false,
                'message'   => 'Unauthorized',
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal access  token');
        $token = $tokenResult->token;

        return response()->json([
            'access_token'  => $tokenResult->accessToken,
            'token_type'    => 'Bearer',
            'expires_at'    => Carbon::parse($token->expires_at)->toDateTimeString(),
            'user'          => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->token()->revoke();
        return response()->json([
            'message' => 'Successfully logout',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
