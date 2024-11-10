<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\TreeController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\HistoryController;
use App\Http\Controllers\Api\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('/trees', [TreeController::class, 'list']);
Route::get('/trees/{id}', [TreeController::class, 'show']);
Route::post('/trees/updateLocation/{id}', [TreeController::class, 'updateLocation']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/profile/show', [ProfileController::class, 'show']);

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/paginated/trees', [TreeController::class, 'paginateIndex']);
    Route::post('/trees', [TreeController::class, 'store']);
    Route::put('/trees/{id}', [TreeController::class, 'update']);
    Route::delete('/trees/{id}', [TreeController::class, 'destroy']);

    Route::get('/history', [HistoryController::class, 'index']);
    Route::get('/history/{id}', [HistoryController::class, 'show']);
});
