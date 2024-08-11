<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SessionController;
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
        Route::post('all', [StudentController::class, 'allStudents']);
    });

    Route::prefix('availability')->group(function () {
        Route::post('add', [StudentController::class, 'addStudentAvailability']);
        Route::post('get', [StudentController::class, 'getStudentAvailability']);
        Route::post('student-for-session', [StudentController::class, 'getAvailableStudents']);
    });

    Route::prefix('schedule')->group(function () {
        Route::post('add', [StudentController::class, 'addStudentSchedule']);
        Route::post('get', [StudentController::class, 'getStudentAvailability']);
        Route::post('student', [SessionController::class, 'scheduleStudent']);
    });

    Route::prefix('session')->group(function () {
        Route::post('add', [SessionController::class, 'addSession']);
        Route::get('get', [SessionController::class, 'getSessions']);
        Route::post('get-session', [SessionController::class, 'getSession']);
        Route::post('add-rating', [SessionController::class, 'addSessionRating']);

        Route::post('add-multiple', [SessionController::class, 'addMultipleSessions']);
        
    });

    Route::prefix('report')->group(function () {
        Route::post('add', [ReportController::class, 'addReport']);
        Route::post('get', [ReportController::class, 'getReport']);
        Route::post('generate-report', [ReportController::class, 'generateReport']);
    });
});