<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' =>  ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @OA\Post(
     *      path="/api/register",
     *      summary="Allows to register a user",
     *      tags={"Register"},
     *      @OA\Parameter(
     *           name="name",
     *           in="query",
     *           description="User personal name",
     *           required=true,
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="email",
     *           in="query",
     *           description="User email (user name)",
     *           required=true,
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="password",
     *           in="query",
     *           required=true,
     *           description="User account password",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="If the user was created",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/User"
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function create(Request $request)
    {
        $fields = $request->only('name', 'email', 'password', 'password_confirmation');
        $validator = $this->validator($fields);
        if ($validator->fails()) {
            return response()->json([
                'success' =>  false,
                'error' => $validator->getMessageBag()->all()]
            );
        }
        return response()->json(User::create([
            'name'  => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]));
    }
}
