<?php

namespace App\Http\Controllers;

use App\Models\TaskGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskGroupsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/tasks/groups",
     *      summary="List all user tasks groups",
     *      description="This does not return the tasks",
     *      tags={"Task manager"},
     *      security={{"bearer": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="List of user task groups",
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
        return response()->json($user->taskGroups()->get());
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'  => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/tasks/groups",
     *      summary="Allows to store a new group",
     *      tags={"Task manager"},
     *      security={{"bearer": {}}},
     *      @OA\Parameter(
     *           name="name",
     *           in="query",
     *           required=true,
     *           description="Name for the task",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="description",
     *           in="query",
     *           required=true,
     *           description="Description for the task group",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Stored group",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/TaskGroup"
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
        $record = $user->taskGroups()->save(new TaskGroup($fields));
        return response()->json($record);
    }

    /**
     * @OA\Patch(
     *      path="/api/tasks/groups/{recordId}",
     *      summary="Update a group task",
     *      tags={"Task manager"},
     *      security={{"bearer": {}}},
     *      @OA\Parameter(
     *           name="name",
     *           in="query",
     *           required=true,
     *           description="Name for the task",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="description",
     *           in="query",
     *           required=true,
     *           description="Description for the task group",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Updated task group",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/TaskGroup"
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function update(Request $request, TaskGroup $record)
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
     *      path="/api/tasks/groups/{recordId}",
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
    public function destroy(Request $request, TaskGroup $record)
    {
        if (($notOwned = $this->checkOwner($request, $record)) !== false) {
            return $notOwned;
        }
        $record->delete();
        return response()->json(null, 204);
    }
}
