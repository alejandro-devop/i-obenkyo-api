<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HabitCategoryController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\HabitFollowUpController;
use App\Http\Controllers\ApiTestController;
use App\Http\Controllers\FrequencyController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskGroupsController;

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
    Route::group(['prefix' => 'accounting'], function () {
        Route::group(['prefix' => 'bills'], function () {
            Route::get('/', [BillController::class, 'index']);
            Route::post('/', [BillController::class, 'store']);
            Route::patch('/{record}', [BillController::class, 'update']);
            Route::delete('/{record}', [BillController::class, 'destroy']);
        });
    });

    Route::group(['prefix' => 'habits'],  function () {
        Route::get('/', [HabitController::class,  'index']);
        Route::get('/daily-follow-up/{dateStr}', [HabitFollowUpController::class, 'dailyFollowUp']);
        Route::post('/', [HabitController::class,  'store']);
        Route::post('/follow-up/{habit}', [HabitFollowUpController::class, 'followUpMark']);
        Route::get('/follow-up/{habit}', [HabitFollowUpController::class, 'followUpList']);
        Route::patch('/{habit}', [HabitController::class,  'update']);
        Route::delete('/{habit}', [HabitController::class,  'destroy']);
        Route::get('/categories',  [HabitCategoryController::class, 'index']);
        Route::post('/categories', [HabitCategoryController::class, 'store']);
        Route::patch('/categories/{habitCategory}', [HabitCategoryController::class, 'update']);
        Route::delete('/categories/{habitCategory}', [HabitCategoryController::class, 'destroy']);
    });

    Route::group(['prefix' => 'settings/frequencies'], function () {
        Route::get('/', [FrequencyController::class, 'index']);
        Route::post('/', [FrequencyController::class, 'store']);
        Route::patch('/{record}', [FrequencyController::class, 'update']);
        Route::delete('/{record}', [FrequencyController::class, 'destroy']);
    });

    Route::group(['prefix' => 'tasks'], function () {
        Route::get('/', [TaskController::class, 'index']);
        Route::post('/', [TaskController::class, 'store']);
        Route::patch('/{record}', [TaskController::class, 'update']);
        Route::delete('/{record}', [TaskController::class, 'destroy']);
        Route::post('/change/{record}', [TaskController::class, 'changeState']);
        Route::get('/groups', [TaskGroupsController::class, 'index']);
        Route::post('/groups', [TaskGroupsController::class, 'store']);
        Route::patch('/groups/{record}', [TaskGroupsController::class, 'update']);
        Route::delete('/groups/{record}', [TaskGroupsController::class, 'destroy']);
    });

});


Route::group(['prefix' => 'test'], function () {
    Route::get('/check', [ApiTestController::class, 'checkGet']);
    Route::post('/check', [ApiTestController::class, 'checkPost']);
    Route::put('/check', [ApiTestController::class, 'checkPut']);
    Route::patch('/check', [ApiTestController::class, 'checkPatch']);
    Route::delete('/check', [ApiTestController::class, 'checkDelete']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {

});
