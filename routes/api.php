<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HabitCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [RegisterController::class, 'create']);
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'habits'],  function () {
        Route::get('/categories',  [HabitCategoryController::class, 'index']);
        Route::post('/categories', [HabitCategoryController::class, 'store']);
        Route::patch('/categories/{habitCategory}', [HabitCategoryController::class, 'update']);
        Route::delete('/categories/{habitCategory}', [HabitCategoryController::class, 'destroy']);
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {

});
