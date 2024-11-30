<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/login', [UserController::class, 'login']);
Route::post('/create/user', [UserController::class, 'create']);
Route::get('/comments', [CommentsController::class, 'comments']);

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/update/user', [UserController::class, 'update']);

    Route::post('/create/comments', [CommentsController::class, 'create']);
    Route::put('/update/comments', [CommentsController::class, 'update']);
    Route::delete('/delete/comment/{id}', [CommentsController::class, 'delete']);
    Route::get('/history/comments', [CommentsController::class, 'history']);
});