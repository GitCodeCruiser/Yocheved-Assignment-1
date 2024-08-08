<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('student')->group(function () {
        Route::get('get', [StudentController::class, 'getStudents']);
        Route::post('add', [StudentController::class, 'addStudent']);
    });

    Route::prefix('availability')->group(function () {
        Route::post('add', [StudentController::class, 'addStudentAvailability']);
        Route::post('get', [StudentController::class, 'getStudentAvailability']);
    });

    Route::prefix('schedule')->group(function () {
        Route::post('add', [StudentController::class, 'addStudentSchedule']);
        Route::post('get', [StudentController::class, 'getStudentAvailability']);
    });
});