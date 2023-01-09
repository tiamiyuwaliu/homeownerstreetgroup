<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/add/owners', [\App\Http\Controllers\HomeController::class, 'store'])->name('add');
Route::get('/delete/owner/{id}', [\App\Http\Controllers\HomeController::class, 'delete'])->where('id', '[0-9]+')->name('delete-owner');
