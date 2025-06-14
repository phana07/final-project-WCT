<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\CourseController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Student API Routes
Route::apiResource('students', StudentController::class);
Route::post('students/{student}/enroll', [StudentController::class, 'enroll']);
Route::post('students/{student}/unenroll', [StudentController::class, 'unenroll']);
Route::get('students/{student}/courses', [StudentController::class, 'courses']);

// Teacher API Routes
Route::apiResource('teachers', TeacherController::class);
Route::get('teachers/{teacher}/courses', [TeacherController::class, 'courses']);

// Course API Routes
Route::apiResource('courses', CourseController::class);
Route::get('courses/{course}/students', [CourseController::class, 'students']);
Route::post('courses/{course}/enroll-student', [CourseController::class, 'enrollStudent']);
Route::post('courses/{course}/unenroll-student', [CourseController::class, 'unenrollStudent']);
