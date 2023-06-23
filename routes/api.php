<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CandidatesController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//public
Route::get('/candidates', [CandidatesController::class, 'index']);
Route::get('/candidate/{user}', [CandidatesController::class, 'show']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/admins', [AdminController::class, 'index']);

//user routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/destroy_self', [UserController::class, 'destroy_self']);
});
//admin routes
Route::group(['middleware' => ['auth:sanctum', 'abilities:admin']], function () {
    Route::post('/make-candidate', [UserController::class, 'make_candidate']);
    Route::post('/make-admin', [UserController::class, 'make_admin']);
    Route::post('/destroy', [UserController::class, 'destroy']);
});

// candidate routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin,candidate']], function () {
    Route::get('/test3', function () {
        return response()->json('test admin ability');
    });
});
