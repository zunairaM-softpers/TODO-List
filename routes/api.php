<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\TODOController;
use App\Models\User;
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

Route::post('register', [UserController::class, 'register']);
Route::get('/email/verify/{id}/{hash}', function (Request $request, User $id) {
    $id->markEmailAsVerified();
    return response()->json([
        "message" => "You're email is verified successfully. You can now login"
    ]);
})->name('verification.verify');

// This route is for redirect purpose when login fails we can't redirect to the view in our case so only message will be displayed
Route::get('login', function() {
    return response()->json([
        "message" => "Login to get access token before hitting any authenticated route."
    ]);
})->name('login');

Route::post('login', [UserController::class, 'login'])->middleware('verified');

Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [UserController::class, 'logout']);
    Route::apiResource('todos', TODOController::class)->except(['edit', 'create']);
    Route::get('filter/{title}', [TODOController::class, 'filter']);
});
