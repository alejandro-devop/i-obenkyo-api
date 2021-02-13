<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskGroup;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/tasks",
     *      summary="List all user tasks grouped",
     *      tags={"Task manager"},
     *      security={{"bearer": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="List of user task grouped",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Items(ref="#/components/schemas/TaskGroup")
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user()?: new User;
        return response()->json($user->getTaskGroups());
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'text'  => ['required', 'string', 'max:255'],
            'description' => ['string'],
            'is_all_day' => ['boolean'],
            'apply_date' => ['string'],
            'group' => ['numeric'],
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/tasks",
     *      summary="Allows to store a new task",
     *      security={{"bearer": {}}},
     *      tags={"Task manager"},
     *      @OA\Parameter(
     *           name="text",
     *           in="query",
     *           description="The task you want to be reminded",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="description",
     *           in="query",
     *           description="",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="is_all_day",
     *           in="query",
     *           description="If the task iss tomething to be executed all day. ",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="group",
     *           in="query",
     *           description="",
     *           @OA\Schema(
     *                  type="integer",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Saved task",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/Task"
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function store(Request $request)
    {
        $validator =  $this->validator($request->all());
        $fields = $validator->validate();
        $group = TaskGroup::findOrFail($fields['group']);
        $fields['task_group_id'] = $fields['group'];
        $fields['is_done'] = false;
        $saved = $group->tasks()->save(new Task($fields));
        return response()->json($saved);
    }

    /**
     * @OA\Patch(
     *      path="/api/tasks/{recordId}",
     *      summary="Allows to update a new task",
     *      security={{"bearer": {}}},
     *      tags={"Task manager"},
     *      @OA\Parameter(
     *           name="text",
     *           in="query",
     *           description="",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="description",
     *           in="query",
     *           description="",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="is_all_day",
     *           in="query",
     *           description="If the task iss tomething to be executed all day. ",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="is_done",
     *           in="query",
     *           description="If the task is done",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="group",
     *           in="query",
     *           description="",
     *           @OA\Schema(
     *                  type="integer",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Saved task",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/Task"
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function update(Request $request, Task $record)
    {
        $validator =  $this->validator($request->all());
        $fields = $validator->validate();
        TaskGroup::findOrFail($fields['group']);
        $fields['task_group_id'] = $fields['group'];
        $record->update($fields);
        return response()->json($record);
    }

    /**
     * @OA\Delete(
     *      path="/api/tasks/{recordId}",
     *      summary="Removes a group from the database",
     *      tags={"Task manager"},
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
    public function destroy(Request $request, Task $record)
    {
        $group = TaskGroup::findOrFail($record->task_group_id);
        if (($notOwned = $this->checkOwner($request, $group)) !== false) {
            return $notOwned;
        }
        $record->delete();
        return response()->json(null, 204);
    }
    /**
     * @OA\Get(
     *      path="/api/tasks/change/{recordId}",
     *      summary="Allows to update the task is_done state",
     *      security={{"bearer": {}}},
     *      tags={"Task manager"},
     *      @OA\Response(
     *          response=200,
     *          description="Updated task",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/Task"
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function changeState(Request $request, Task $record)
    {
        $group = TaskGroup::findOrFail($record->task_group_id);
        if (($notOwned = $this->checkOwner($request, $group)) !== false) {
            return $notOwned;
        }
        $record->is_done = !$record->is_done;
        $record->update();
        return response()->json($record);
    }
}
