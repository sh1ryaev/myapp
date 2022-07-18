<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\PostsController;
use App\Http\Controllers\Api\v1\PostsPhotosController;
use App\Http\Controllers\Api\v1\CategoriesController;
use App\Http\Controllers\Api\v1\UserLikeController;

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
//
//
//Route::post('/auth/register', [AuthController::class, 'createUser']);
//Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::resource('posts', PostsController::class);
Route::resource('posts-photos', PostsPhotosController::class);
Route::resource('categories', CategoriesController::class);
Route::get('/all-user-like/{id}', [UserLikeController::class, 'getAll']);

