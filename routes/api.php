<?php

use App\Http\Controllers\Api\AuthApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::match(['post', 'get'], 'login', [AuthApiController::class, 'login']);
Route::post('register', [AuthApiController::class, 'register']);


Route::get('view/{id}', [AuthApiController::class, 'view']);
Route::get('showAll/', [AuthApiController::class, 'showAll']);
Route::patch('update/{id}', [AuthApiController::class, 'update']);
