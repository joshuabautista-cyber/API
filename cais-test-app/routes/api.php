<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Class_SchedController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\PreregistrationController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

    return $request->user();
});

// Public routes (no auth required)
Route::get('/subjects-schedule', [RegistrationController::class, 'getPublicSubjectsSchedule']);

// Pre-registration routes
Route::prefix('prereg')->group(function () {
    Route::get('/active-semester', [RegistrationController::class, 'getActiveSemester']);
    Route::get('/all-subjects', [RegistrationController::class, 'getAllSubjectsOffered']);
    Route::get('/subjects-offered', [RegistrationController::class, 'getSubjectsOffered']);
    
    // New preregistration endpoints
    Route::get('/user-courses', [PreregistrationController::class, 'getUserPreregistrations']);
    Route::post('/add', [PreregistrationController::class, 'store']);
    Route::delete('/remove/{id}', [PreregistrationController::class, 'destroy']);
    Route::post('/enroll', [PreregistrationController::class, 'enrollCourse']);
    Route::post('/enroll-all', [PreregistrationController::class, 'enrollAll']);
    
    // Legacy endpoint - redirects to new controller
    Route::get('/preregistered-subjects', [PreregistrationController::class, 'getUserPreregistrations']);
});


Route::apiResource('semesters', SemesterController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('colleges', CollegeController::class);
Route::apiResource('departments', DepartmentController::class);
Route::apiResource('profiles', ProfileController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('class_sched', Class_SchedController::class);
Route::apiResource('enrollments', EnrollmentController::class);
Route::apiResource('grades', GradesController::class);
Route::apiResource('registrations', RegistrationController::class);
Route::apiResource('preregistrations', PreregistrationController::class);

