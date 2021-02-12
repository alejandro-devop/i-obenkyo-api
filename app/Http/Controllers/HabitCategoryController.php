<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HabitCategory;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class HabitCategoryController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/habits/categories",
     *      summary="Allows to list habit categories",
     *      tags={"Habits"},
     *      security={{"bearer": {}}},
     * 
     *      @OA\Response(
     *          response=200,
     *          description="Categories list",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Items(ref="#/components/schemas/HabitCategory"),
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user()?: new User;
        return response()->json($user->habitCategories()->get());
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'  => ['required', 'string', 'max:255'],
            'icon'  => ['string', 'max:100']
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/habits/categories",
     *      summary="Allows to save a new habit category",
     *      security={{"bearer": {}}},
     *      tags={"Habits"},
     *      @OA\Parameter(
     *           name="name",
     *           in="query",
     *           description="Name assigned to the category",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="icon",
     *           in="query",
     *           description="Icon to be assinged to the category, see: https://fontawesome.com/icons?d=gallery",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Saved category",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/HabitCategory"
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function store(Request $request)
    {
        $user = $request->user()?: new User;
        $fields = $request->only('name', 'icon');
        $this->validator($request->all())->validate();
        $category = $user->habitCategories()->save(new HabitCategory($fields));
        return response()->json($category);
    }

    /**
     * @OA\Patch(
     *      path="/api/habits/categories",
     *      summary="Allows to update a habit category",
     *      tags={"Habits"},
     *      security={{"bearer": {}}},
     *      @OA\Parameter(
     *           name="name",
     *           in="query",
     *           description="Name assigned to the category",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           name="icon",
     *           in="query",
     *           description="Icon to be assinged to the category, see: https://fontawesome.com/icons?d=gallery",
     *           @OA\Schema(
     *                  type="string",
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="If the category was updated successfuly",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      ref="#/components/schemas/HabitCategory"
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function update(Request $request, HabitCategory $habitCategory)
    {
        $fields = $request->only('name', 'icon');
        if (($notOwned = $this->checkOwner($request, $habitCategory)) !== false) {
            return $notOwned;
        }
        $this->validator($request->all())->validate();
        $habitCategory->update($fields);
        return response()->json($habitCategory, 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/habits/categories",
     *      summary="Allows to remove a category",
     *      tags={"Habits"},
     *      security={{"bearer": {}}},
     *      @OA\Response(
     *          response=204,
     *          description="If the category was removed successfuly",
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
    public function destroy(Request $request, HabitCategory $habitCategory)
    {
        if (($notOwned = $this->checkOwner($request, $habitCategory)) !== false) {
            return $notOwned;
        }
        // Todo: Validate associated habits
        $habitCategory->delete();
        return response()->json(null, 204);
    }
}
