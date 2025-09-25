<?php

use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\LevelThemeController;
use App\Http\Controllers\Api\QuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::apiResource('/questions', QuestionController::class);
Route::apiResource('/answers', AnswerController::class);
Route::apiResource('/levels', LevelController::class);
Route::apiResource('/themes', LevelThemeController::class);
Route::post('/answers/submitAnswer', [AnswerController::class, 'submitAnswer']);
Route::post('/answers/submitAnswers', [AnswerController::class, 'submitAnswers']);
