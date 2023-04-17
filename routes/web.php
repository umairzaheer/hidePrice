<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RuleController;
use App\Models\Rule;

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
    // return view('welcome');
    Route::get('/', [SettingController::class, 'index'])->name('home');
    Route::get('general-setting-form', [SettingController::class, 'generalSettingForm'])->name('general_setting_form');
    Route::post('/store-setting', [SettingController::class, 'store']);
    Route::get('/add-rule-form', [RuleController::class, 'index'])->name('add_rule_form');
    Route::post('/add-rule', [RuleController::class, 'store']);
    Route::get('edit-rule/{id}', [RuleController::class, 'edit'])->name('edit_rule_form');
    Route::post('update-rule/{id}', [RuleController::class, 'update']);
    Route::delete('delete-rule/{id}', [RuleController::class, 'destroy']);
    Route::get('/get-all-products', [RuleController::class, 'getAllProducts']);
    Route::get('/pagination', [RuleController::class, 'pagination']);

    Route::get('load-rule-data', [RuleController::class, 'loadRuleData']);
    
});
