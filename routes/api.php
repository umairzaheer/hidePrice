<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RuuleController;

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


// Route::middleware(['verify.shopify'])->group(function () {
    // Route::get('/match-product', [CourseController::class, 'matchProduct']);
    Route::get('/get-product-handle', [CourseController::class, 'getproducthandle']);
    Route::post('/order-status', [CourseController::class, 'getorderstatus']);
    Route::post('/order-review', [CourseController::class, 'orderreview']);
    Route::get('/star-rating', [CourseController::class, 'starRating']);

    Route::get('/match-product', [RuuleController::class, 'matchProduct']);
// });
