<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RuuleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware(['verify.shopify'])->group(function () {
    Route::get('students', [StudentController::class, 'index']);
    Route::post('students', [StudentController::class, 'store']);
    Route::get('fetch-students', [StudentController::class, 'fetchstudent']);
    Route::get('edit-student/{id}', [StudentController::class, 'edit']);
    Route::put('update-student/{id}', [StudentController::class, 'update']);
    Route::delete('delete-student/{id}', [StudentController::class, 'destroy']);
    Route::get('search', [StudentController::class, 'search']);




    Route::get('teachers', [TeacherController::class, 'index']);
    Route::post('teachers', [TeacherController::class, 'store']);
    Route::get('fetch-teachers', [TeacherController::class, 'fetchteacher']);
    Route::get('edit-teacher/{id}', [TeacherController::class, 'edit']);
    Route::put('update-teacher/{id}', [TeacherController::class, 'update']);
    Route::delete('delete-teacher/{id}', [TeacherController::class, 'destroy']);
    Route::get('search', [TeacherController::class, 'search']);


    Route::get('/index', [CourseController::class, 'index'])->name('home');
    Route::get('insert-course', [CourseController::class, 'insertcourse']);
    Route::post('courses', [CourseController::class, 'store']);
    Route::get('fetch-courses', [CourseController::class, 'fetchcourse']);
    Route::get('edit-course/{id}', [CourseController::class, 'edit']);
    Route::put('update-course/{id}', [CourseController::class, 'update']);
    Route::delete('delete-course/{id}', [CourseController::class, 'destroy']);
    Route::get('search', [CourseController::class, 'search']);

    Route::get('/get-all-products', [CourseController::class, 'getallproducts']);
    Route::post('store-product', [CourseController::class, 'storeProduct']);

    Route::get('/', [RuuleController::class, 'index'])->name('home');
    Route::get('add-rule', [RuuleController::class, 'addRule']);
    Route::POST('rules', [RuuleController::class, 'store']);
    Route::get('edit-rule/{id}', [RuuleController::class, 'edit']);
    // Route::get('edit-rule/{id}/edit', [RuuleController::class, 'edit'])->name('edit-rule.edit');
    Route::put('update-rule/{id}', [RuuleController::class, 'update']);
    Route::get('/get-all-products', [RuuleController::class, 'getallproducts']);
    // Route::get('delete-rule/{id}', [RuuleController::class, 'destroy']);
    Route::delete('delete-rule/{id}', [RuuleController::class, 'destroy']);
    Route::get('search', [RuuleController::class, 'search']);
    Route::get('pagination', [RuuleController::class, 'pagination']);


});
