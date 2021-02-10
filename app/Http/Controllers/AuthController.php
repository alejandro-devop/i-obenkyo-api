<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @OA\Info(title="IObenkyo api", version="1.0")
     * @OA\Post(
     *      path="/api/auth/login",
     *      summary="Allows to authenticate the user in the api",
     *      tags={"Auth"},
     *      @OA\Parameter(
     *           name="email",
     *           in="query",
     *           description="User's account email",
     *           required=true,
     *           @OA\Schema(
     *           type="string",
     *         ),
     *      ),
     *      @OA\Parameter(
     *           name="password",
     *           in="query",
     *           description="User's account password",
     *           required=true,
     *           @OA\Schema(
     *           type="string",
     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Authentication success",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                          property="access_token",
     *                          type="string",
     *                          description="User authenticated token",
     *                      ),
     *                      @OA\Property(
     *                          property="token_type",
     *                          type="string",
     *                          default="Bearer"
     *                      ),
     *                      @OA\Property(
     *                          property="expires_at",
     *                          type="string",
     *                          default="YYYY-MM-DD HH:mm:ss"
     *                      ),
     *                      @OA\Property(
     *                          property="user",
     *                          ref="#/components/schemas/User",
     *                      ),
     *                  )
     *              )
     *          }
     *      ),
     *      @OA\Response(response=422, description="If the user does not send the required params"),
     *      @OA\Response(response=401, description="If the email or password are wrong"),
     * )
     */
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

    /**
     * @OA\Get(
     *      path="/api/auth/logout",
     *      summary="Allows to remoke the user token",
     *      security={{ "bearer": {} }},
     *      tags={"Auth"},
     *      @OA\Response(response=401, description="It the user is no logged in"),
     *      @OA\Response(
     *          response=200,
     *          description="Success logged out",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                          property="message",
     *                          type="string",
     *                          default="Successfully logout",
     *                      ),
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
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
