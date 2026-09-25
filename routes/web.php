<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\courseController;


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

Route::get('/', function () {
    return view('admin.dashboard.index');
});

Route::get('/curso/list', [courseController::class , 'index'])->name('course.index');
Route::get('/curso/adicionar', [courseController::class , 'create'])->name('course.create');
Route::get('/curso/{$id}', [courseController::class , 'show'])->name('course.show');
Route::get('/curso/edit/{$id}', [courseController::class , 'edit'])->name('course.edit');
Route::put('/curso/update/{$id}', [courseController::class , 'update']);
Route::post('/curso', [courseController::class , 'store']);
Route::delete('/curso/{id}', [courseController::class , 'destroy']);