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
    return view('dashboard.index');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
});

Route::get('/dashboard/main', function () {
    return view('dashboard.index');
});

/*
|--------------------------------------------------------------------------
| Rotas do Módulo de Cursos (Course CRUD)
|--------------------------------------------------------------------------
|
| Gestão completa de cursos: Listar, Criar, Salvar, Ver, Editar, Atualizar e Eliminar.
|
*/

// Rota para a listagem de todos os cursos
Route::get('/course/index', [courseController::class, 'index'])->name('course.index');

// Rota para apresentar o formulário de criação de novo curso
Route::get('/course/create', [courseController::class, 'create'])->name('course.create');

// Rota POST para processar a gravação de um novo curso
Route::post('/course/store', [courseController::class, 'store'])->name('course.store');

// Rota para visualizar os detalhes de um curso específico por ID
Route::get('/course/{id}', [courseController::class, 'show'])->name('course.show');

// Rota para apresentar o formulário de edição de um curso existente por ID
Route::get('/course/edit/{id}', [courseController::class, 'edit'])->name('course.edit');

// Rota PUT para processar a atualização dos dados do curso por ID
Route::put('/course/update/{id}', [courseController::class, 'update'])->name('course.update');

// Rota DELETE para eliminar um curso da base de dados por ID
Route::delete('/course/destroy/{id}', [courseController::class, 'destroy'])->name('course.destroy');

Route::get('/student/index', [studentController::class, 'index']);
Route::get('/student/create', [studentController::class, 'create']);
Route::get('/student/{id}', [studentController::class, 'show']);

Route::get('/teacher/index', [teacherController::class, 'index']);
Route::get('/teacher/create', [teacherController::class, 'create']);
Route::get('/teacher/{id}', [teacherController::class, 'show']);