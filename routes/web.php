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
| Rotas Web da Aplicação
|--------------------------------------------------------------------------
| Mapeamento de todas as URLs para os respectivos controladores e acções.
*/

// Página Principal (Dashboard)
Route::get('/', function () {
    return view('admin.dashboard.index');
});

// Rotas de Gestão de Cursos
Route::get('/curso/listar', [courseController::class, 'index'])->name('course.index');
Route::get('/curso/adicionar', [courseController::class, 'create'])->name('course.create');
Route::post('/curso', [courseController::class, 'store'])->name('course.store');
Route::get('/curso/{id}', [courseController::class, 'show'])->name('course.show');
Route::get('/curso/edit/{id}', [courseController::class, 'edit'])->name('course.edit');
Route::put('/curso/update/{id}', [courseController::class, 'update'])->name('course.update');
Route::delete('/curso/{id}', [courseController::class, 'destroy'])->name('course.destroy');

// Rotas de Gestão de Formadores (Professores)
Route::get('/formador/listar', [teacherController::class, 'index'])->name('teacher.index');
Route::get('/formador/adicionar', [teacherController::class, 'create'])->name('teacher.create');
Route::post('/formador', [teacherController::class, 'store'])->name('teacher.store');
Route::get('/formador/{id}', [teacherController::class, 'show'])->name('teacher.show');
Route::get('/formador/edit/{id}', [teacherController::class, 'edit'])->name('teacher.edit');
Route::put('/formador/update/{id}', [teacherController::class, 'update'])->name('teacher.update');
Route::delete('/formador/{id}', [teacherController::class, 'destroy'])->name('teacher.destroy');

// Rotas de Gestão de Turmas / Salas
Route::get('/turma/listar', [roomController::class, 'index'])->name('room.index');
Route::get('/turma/adicionar', [roomController::class, 'create'])->name('room.create');
Route::post('/turma', [roomController::class, 'store'])->name('room.store');
Route::get('/turma/{id}', [roomController::class, 'show'])->name('room.show');
Route::get('/turma/edit/{id}', [roomController::class, 'edit'])->name('room.edit');
Route::put('/turma/update/{id}', [roomController::class, 'update'])->name('room.update');
Route::delete('/turma/{id}', [roomController::class, 'destroy'])->name('room.destroy');

// Rotas de Gestão de Formandos (Alunos)
Route::get('/formando/listar', [studentController::class, 'index'])->name('student.index');
Route::get('/formando/adicionar', [studentController::class, 'create'])->name('student.create');
Route::post('/formando', [studentController::class, 'store'])->name('student.store');
Route::get('/formando/{id}', [studentController::class, 'show'])->name('student.show');
Route::get('/formando/edit/{id}', [studentController::class, 'edit'])->name('student.edit');
Route::put('/formando/update/{id}', [studentController::class, 'update'])->name('student.update');
Route::delete('/formando/{id}', [studentController::class, 'destroy'])->name('student.destroy');

// Rotas de Gestão de Inscrições / Matrículas
Route::get('/inscricao/listar', [enrollmentController::class, 'index'])->name('enrollment.index');
Route::get('/inscricao/adicionar', [enrollmentController::class, 'create'])->name('enrollment.create');
Route::post('/inscricao', [enrollmentController::class, 'store'])->name('enrollment.store');
Route::get('/inscricao/{id}', [enrollmentController::class, 'show'])->name('enrollment.show');
Route::get('/inscricao/edit/{id}', [enrollmentController::class, 'edit'])->name('enrollment.edit');
Route::put('/inscricao/update/{id}', [enrollmentController::class, 'update'])->name('enrollment.update');
Route::delete('/inscricao/{id}', [enrollmentController::class, 'destroy'])->name('enrollment.destroy');

// Rotas de Gestão de Pagamentos
Route::get('/pagamento/listar', [paymentController::class, 'index'])->name('payment.index');
Route::get('/pagamento/adicionar', [paymentController::class, 'create'])->name('payment.create');
Route::post('/pagamento', [paymentController::class, 'store'])->name('payment.store');
Route::get('/pagamento/{id}', [paymentController::class, 'show'])->name('payment.show');
Route::get('/pagamento/edit/{id}', [paymentController::class, 'edit'])->name('payment.edit');
Route::put('/pagamento/update/{id}', [paymentController::class, 'update'])->name('payment.update');
Route::delete('/pagamento/{id}', [paymentController::class, 'destroy'])->name('payment.destroy');