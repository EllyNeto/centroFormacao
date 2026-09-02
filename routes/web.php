<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\courseController;
use App\Http\Controllers\studentController;
use App\Http\Controllers\teacherController;

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
    return view('dashboard.main');
});

Route::get('/dashboard/main', function () {
    return view('dashboard.main');
});

Route::get('/finance/index', function () {
    return view('finance.index');
});

Route::get('/student/index', [studentController::class, 'index']);
Route::get('/student/create', [studentController::class, 'create']);
Route::get('/student/{id}', [studentController::class, 'show']);

Route::get('/teacher/index', [teacherController::class, 'index']);
Route::get('/teacher/create', [teacherController::class, 'create']);
Route::get('/teacher/{id}', [teacherController::class, 'show']);