<?php

declare(strict_types = 1);

use App\Http\Controllers\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//region autenticada
Route::middleware('auth:sanctum')->group(function () {
    // region Questions
    Route::post('/questions', Question\StoreController::class)->name('questions.store');
    // endregion
});
//endregion
