<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [RegisteredUserController::class, 'register']);
Route::post('/login', [AuthenticatedSessionController::class, 'login']);
Route::group([],function () {
    Route::post('order-dish', ['uses' => 'App\Http\Controllers\OrderController@order']);
    Route::get('order-stats/2-days', ['uses' => 'App\Http\Controllers\OrderController@lastTwoDayStats']);
    Route::get('order-stats/10-days', ['uses' => 'App\Http\Controllers\OrderController@lastTenDayStats']);
});

