<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\courseController;
use App\Http\Controllers\teacherController;
use App\Http\Controllers\roomController;
use App\Http\Controllers\studentController;
use App\Http\Controllers\enrollmentController;
use App\Http\Controllers\paymentController;


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

Route::get('/curso/listar', [courseController::class , 'index'])->name('course.index');
Route::get('/curso/adicionar', [courseController::class , 'create'])->name('course.create');
Route::get('/curso/{$id}', [courseController::class , 'show'])->name('course.show');
Route::get('/curso/edit/{$id}', [courseController::class , 'edit'])->name('course.edit');
Route::put('/curso/update/{$id}', [courseController::class , 'update']);
Route::post('/curso', [courseController::class , 'store']);
Route::delete('/curso/{id}', [courseController::class , 'destroy']);

Route::get('/formador/listar', [teacherController::class , 'index'])->name('teacher.index');
Route::get('/formador/adicionar', [teacherController::class , 'create'])->name('teacher.create');
Route::get('/formador/{$id}', [teacherController::class , 'show'])->name('teacher.show');
Route::get('/formador/edit/{$id}', [teacherController::class , 'edit'])->name('teacher.edit');
Route::put('/formador/update/{$id}', [teacherController::class , 'update']);
Route::post('/formador', [teacherController::class , 'store']);
Route::delete('/formador/{id}', [teacherController::class , 'destroy']);

Route::get('/turma/listar', [roomController::class , 'index'])->name('room.index');
Route::get('/turma/adicionar', [roomController::class , 'create'])->name('room.create');
Route::get('/turma/{$id}', [roomController::class , 'show'])->name('room.show');
Route::get('/turma/edit/{$id}', [roomController::class , 'edit'])->name('room.edit');
Route::put('/turma/update/{$id}', [roomController::class , 'update']);
Route::post('/turma', [roomController::class , 'store']);
Route::delete('/turma/{id}', [roomController::class , 'destroy']);

Route::get('/formando/listar', [studentController::class , 'index'])->name('student.index');
Route::get('/formando/{$id}', [studentController::class , 'show'])->name('student.show');
Route::get('/formando/edit/{$id}', [studentController::class , 'edit'])->name('student.edit');
Route::put('/formando/update/{$id}', [studentController::class , 'update']);
Route::post('/formando', [studentController::class , 'store']);
Route::delete('/formando/{id}', [studentController::class , 'destroy']);

Route::get('/inscricao/listar', [enrollmentController::class , 'index'])->name('enrollment.index');
Route::get('/inscricao/adicionar', [enrollmentController::class , 'create'])->name('enrollment.create');
Route::get('/inscricao/{$id}', [enrollmentController::class , 'show'])->name('enrollment.show');
Route::get('/inscricao/edit/{$id}', [enrollmentController::class , 'edit'])->name('enrollment.edit');
Route::put('/inscricao/update/{$id}', [enrollmentController::class , 'update']);
Route::post('/inscricao', [enrollmentController::class , 'store']);
Route::delete('/inscricao/{id}', [enrollmentController::class , 'destroy']);

Route::get('/pagamento/listar', [paymentController::class , 'index'])->name('payment.index');
Route::get('/pagamento/adicionar', [paymentController::class , 'create'])->name('payment.create');
Route::get('/pagamento/{$id}', [paymentController::class , 'show'])->name('payment.show');
Route::get('/pagamento/edit/{$id}', [paymentController::class , 'edit'])->name('payment.edit');
Route::put('/pagamento/update/{$id}', [paymentController::class , 'update']);
Route::post('/pagamento', [paymentController::class , 'store']);
Route::delete('/pagamento/{id}', [paymentController::class , 'destroy']);