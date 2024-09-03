<?php

declare(strict_types = 1);

use App\Http\Controllers\Auth\{LoginController, LogoutController, RegisterController};
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


Route::middleware(['guest', 'web'])->group(function () {
    Route::post('login', LoginController::class)->name('login');
    Route::post('register', RegisterController::class)->name('register');
});

Route::post('logout', LogoutController::class)
    ->middleware(['auth'])
    ->name('logout');

//region autenticada
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', fn (Request $request) => $request->user());
    // region Questions
    Route::post('/questions', Question\StoreController::class)->name('questions.store');
    Route::put('/questions/{question}', Question\UpdateController::class)->name('questions.update');
    Route::delete('/questions/{question}', Question\DeleteController::class)->name('questions.delete');
    Route::delete('/questions/{question}/archive', Question\ArchiveController::class)->name('questions.archive');
    Route::put('/questions/{question}/restore', Question\RestoreController::class)->name('questions.restore');
    Route::put('/questions/{question}/publish', Question\PublishController::class)->name('questions.publish');
    // endregion
});
//endregion
