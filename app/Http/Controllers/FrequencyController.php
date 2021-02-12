<?php

namespace App\Http\Controllers;

use App\Models\FrequencyType;
use App\Models\HabitCategory;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class FrequencyController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/settings/frequencies",
     *      summary="Lists all frequencies",
     *      tags={"Settings"},
     *      security={{"bearer": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="List of application frequencies",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Items(ref="#/components/schemas/Frequency")
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
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

    /**
     * @OA\Post(
     *      path="/api/settings/frequencies",
     *      summary="Allows to store a new frequency",
     *      tags={"Settings"},
     *      security={{"bearer": {}}},
     *      @OA\Parameter(
     *           name="name",
     *           in="query",
     *           required=true,
     *           description="",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="days",
     *           in="query",
     *           description="Days applied for the frequency",
     *           @OA\Schema(
     *                  type="integer",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="is_daily",
     *           in="query",
     *           description="If the frequency applies daily",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="is_weekly",
     *           in="query",
     *           description="If the frequency applies every week",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="is_monthly",
     *           in="query",
     *           description="If the frequency applies every month",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="is_every_year",
     *           in="query",
     *           description="If the frequency applies every year",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="If the frequency was stored",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/Frequency"
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function store(Request $request)
    {
        $user = $request->user()?: new User;
        $validator =  $this->validator($request->all());
        $fields = $validator->validate();
        $record = $user->frequencies()->save(new FrequencyType($fields));
        return response()->json($record);
    }

    /**
     * @OA\Patch(
     *      path="/api/settings/frequencies/{recordId}",
     *      summary="Allows to update a frequency",
     *      tags={"Settings"},
     *      security={{"bearer": {}}},
     *      @OA\Parameter(
     *           name="name",
     *           in="query",
     *           required=true,
     *           description="",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="days",
     *           in="query",
     *           description="Days applied for the frequency",
     *           @OA\Schema(
     *                  type="integer",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="is_daily",
     *           in="query",
     *           description="If the frequency applies daily",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="is_weekly",
     *           in="query",
     *           description="If the frequency applies every week",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="is_monthly",
     *           in="query",
     *           description="If the frequency applies every month",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="is_every_year",
     *           in="query",
     *           description="If the frequency applies every year",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="the updated frequency",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/Frequency"
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
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

    /**
     * @OA\Delete(
     *      path="/api/settings/frequencies/{recordId}",
     *      summary="Remove a frequency",
     *      tags={"Settings"},
     *      security={{"bearer": {}}},
     *      @OA\Response(
     *          response=204,
     *          description="",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
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
