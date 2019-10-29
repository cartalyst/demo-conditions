<?php

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

use App\Http\Controllers\Demo\HomeController;
use App\Http\Controllers\Demo\ConditionsController;

Route::get('/', function () {
    return redirect('/demo');
    // return view('welcome');
});

Route::prefix('/demo')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('demo.home');

    Route::get('regular', [ConditionsController::class, 'regular'])->name('demo.regular');
    Route::get('percentage', [ConditionsController::class, 'percentage'])->name('demo.percentage');
    Route::get('inclusive', [ConditionsController::class, 'inclusive'])->name('demo.inclusive');

    Route::post('process', [ConditionsController::class, 'process'])->name('demo.process');
});
