<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\FrequencyType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Bills management
 * @version 1.0.0
 * @author Alejandro Quiroz <alejandro.devop@gmail.com>
 */
class BillController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/accounting/bills",
     *      summary="Lists user bills",
     *      security={{"bearer": {}}},
     *      tags={"Accounting", "Bills"},
     *      @OA\Response(
     *          response=200,
     *          description="User created bills",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Items(ref="#/components/schemas/Bill"),
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
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

    /**
     * @OA\Post(
     *      path="/api/accounting/bills",
     *      summary="Allows to store a new bill",
     *      tags={"Accounting", "Bills"},
     *      security={{"bearer": {}}},
     *      @OA\Parameter(
     *           name="name",
     *           in="query",
     *           description="A title for the bill",
     *           required=true,
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="apply_date",
     *           in="query",
     *           description="Date: YYYY-MM-DD HH:mm:ss",
     *           required=true,
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="frequency",
     *           in="query",
     *           description="Id of the frequency applied to the bill",
     *           required=true,
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="value",
     *           in="query",
     *           description="Value or price for the bill",
     *           required=true,
     *           @OA\Schema(
     *                  type="float",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="If the bill was saved",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/Bill"
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
        $frequency = FrequencyType::findOrFail($fields['frequency']);
        $fields['frequency_id'] = $frequency->id;
        $saved = $user->bills()->save(new Bill($fields));
        $bill = Bill::with('frequency')->where('id', $saved->id)->first();
        return response()->json($bill);
    }

    /**
     * @OA\Patch(
     *      path="/api/accounting/bills/{recordId}",
     *      summary="Allows to update a selected bill",
     *      tags={"Accounting", "Bills"},
     *      security={{"bearer": {}}},
     *      @OA\Parameter(
     *           name="name",
     *           in="query",
     *           description="A title for the bill",
     *           required=true,
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="apply_date",
     *           in="query",
     *           description="Date: YYYY-MM-DD HH:mm:ss",
     *           required=true,
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="frequency",
     *           in="query",
     *           description="Id of the frequency applied to the bill",
     *           required=true,
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="value",
     *           in="query",
     *           description="Value or price for the bill",
     *           required=true,
     *           @OA\Schema(
     *                  type="float",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="If the record was updated successfully",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/Bill"
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
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

    /**
     * @OA\Delete(
     *      path="/api/accounting/bills/{recordId}",
     *      summary="Allows to remove a bill",
     *      tags={"Accounting", "Bills"},
     *      security={{"bearer": {}}},
     *      @OA\Response(
     *          response=204,
     *          description="If the Bill was removed",
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
    public function destroy(Request $request, Bill $record)
    {
        if (($notOwned = $this->checkOwner($request, $record)) !== false) {
            return $notOwned;
        }
        $record->delete();
        return response()->json(null, 204);
    }
}
