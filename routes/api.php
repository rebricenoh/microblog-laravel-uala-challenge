<?php

//use Illuminate\Http\Request;

use App\Infrastructure\Controllers\TweetController;
use App\Infrastructure\Controllers\UserController;
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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::post('/tweets', [TweetController::class, 'create']);
Route::get('/timeline', [TweetController::class, 'timeline']);
Route::post('/follow', [UserController::class, 'follow']);
