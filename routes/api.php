<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

//user routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/test', function () {
        return response()->json('test user ability');
    });
});
//admin routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin']], function () {
    Route::get('/test2', function () {
        return response()->json('test admin ability');
    });
});

// candidate routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin,candidate']], function () {
    Route::get('/test3', function () {
        return response()->json('test admin ability');
    });
});
//test
