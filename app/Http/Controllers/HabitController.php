<?php

namespace App\Http\Controllers;

use App\Models\HabitCategory;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class HabitController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/habits",
     *      summary="List of habits",
     *      tags={"Habits"},
     *      @OA\Response(
     *          response=200,
     *          description="List of habits",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Items(ref="#/components/schemas/Habit")
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user()?: new User;
        $records = $user->getHabits();
        return response()->json($records);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['string'],
            'is_counter'    => ['boolean'],
            'counter_goal'  => ['numeric'],
            'start'         => ['required', 'date'],
            'should_keep'   => ['boolean'],
            'should_avoid'  => ['boolean'],
            'goal_date'     => ['required', 'date', 'after:start_date'],
            'streak_goal'   => ['required', 'numeric',  'gte:7'],
            'category'      => ['required', 'numeric'],
        ]);
    }


    /**
     * @OA\Post(
     *      path="/api/habits",
     *      summary="Allows to store a new habit",
     *      tags={"Habits"},
     *      @OA\Parameter(
     *           name="title",
     *           in="query",
     *           required=true,
     *           description="A title to identify the habit",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="description",
     *           in="query",
     *           description="Additional information for the habit",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="is_counter",
     *           in="query",
     *           description="If the habit is complete by counting",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="counter_goal",
     *           in="query",
     *           description="Amount required to complete the habit",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="start",
     *           in="query",
     *           description="Date when the habit should be start getting tracked (YYYY-MM-DD HH:mm:ss)",
     *           required=true,
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="should_keep",
     *           in="query",
     *           description="If the habit its something that benefits the user",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="should_avoid",
     *           in="query",
     *           description="If the habit its something that harms the user",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="goal_date",
     *           in="query",
     *           description="Date when the habit should be completed (YYYY-MM-DD HH:mm:ss)",
     *           required=true,
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="streak_goal",
     *           in="query",
     *           description="What is the goal for the habit",
     *           required=true,
     *           @OA\Schema(
     *                  type="integer",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="category",
     *           in="query",
     *           description="",
     *           required=true,
     *           @OA\Schema(
     *                  type="integer",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="The stored category",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/Habit"
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
        $category = HabitCategory::findOrFail($fields['category']);
        $fields['category_id'] = $category->id;
        $fields['streak_count'] = 0;
        $habitSaved = $user->habits()->save(new Habit($fields));
        $habit = Habit::with('category')->with('followUps')->where('id', $habitSaved->id)->first();
        return response()->json($habit);
    }

    /**
     * @OA\Patch(
     *      path="/api/habits",
     *      summary="Allows to update a habit",
     *      tags={"Habits"},
     *      @OA\Parameter(
     *           name="title",
     *           in="query",
     *           required=true,
     *           description="A title to identify the habit",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="description",
     *           in="query",
     *           description="Additional information for the habit",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="is_counter",
     *           in="query",
     *           description="If the habit is complete by counting",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="counter_goal",
     *           in="query",
     *           description="Amount required to complete the habit",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="start",
     *           in="query",
     *           description="Date when the habit should be start getting tracked (YYYY-MM-DD HH:mm:ss)",
     *           required=true,
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="should_keep",
     *           in="query",
     *           description="If the habit its something that benefits the user",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="should_avoid",
     *           in="query",
     *           description="If the habit its something that harms the user",
     *           @OA\Schema(
     *                  type="boolean",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="goal_date",
     *           in="query",
     *           description="Date when the habit should be completed (YYYY-MM-DD HH:mm:ss)",
     *           required=true,
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="streak_goal",
     *           in="query",
     *           description="What is the goal for the habit",
     *           required=true,
     *           @OA\Schema(
     *                  type="integer",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="category",
     *           in="query",
     *           description="",
     *           required=true,
     *           @OA\Schema(
     *                  type="integer",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="The stored category",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/Habit"
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function update(Request $request, Habit $habit)
    {
        if (($notOwned = $this->checkOwner($request, $habit)) !== false) {
            return $notOwned;
        }
        $fields = $this->validator($request->all())->validate();
        $category = HabitCategory::findOrFail($fields['category']);
        $fields['category_id'] = $category->id;
        $habit->update($fields);
        return response()->json($habit, 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/habits",
     *      summary="Allows to remove a habit",
     *      tags={"Habits"},
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
    public function destroy(Request $request, Habit $habit)
    {
        if (($notOwned = $this->checkOwner($request, $habit)) !== false) {
            return $notOwned;
        }
        // Todo: Validate associated habits
        $habit->delete();
        return response()->json(null, 204);
    }
}
