<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\courseController;
use App\Http\Controllers\studentController;
use App\Http\Controllers\teacherController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\classController;

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

Route::get('/dashboard/main', function () {
    return view('admin.dashboard.index');
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
Route::get('/course/index', [courseController::class, 'index'])->name('course.index'); // http://127.0.0.1:8000/course/index

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

/*
|--------------------------------------------------------------------------
| Rotas do Módulo de Estudantes (Student CRUD)
|--------------------------------------------------------------------------
|
| Gestão completa de estudantes: Listar, Criar, Salvar, Ver, Editar, Atualizar e Eliminar.
|
*/

// Rota GET para a listagem de todos os estudantes registados
Route::get('/student/index', [studentController::class, 'index'])->name('student.index');

// Rota GET para apresentar o formulário de registo de novo estudante
Route::get('/student/create', [studentController::class, 'create'])->name('student.create');

// Rota POST para processar a gravação dos dados do novo estudante
Route::post('/student/store', [studentController::class, 'store'])->name('student.store');

// Rota GET para visualizar os detalhes de um estudante específico por ID
Route::get('/student/{id}', [studentController::class, 'show'])->name('student.show');

// Rota GET para apresentar o formulário de edição de um estudante existente por ID
Route::get('/student/edit/{id}', [studentController::class, 'edit'])->name('student.edit');

// Rota PUT para processar a atualização dos dados do estudante por ID
Route::put('/student/update/{id}', [studentController::class, 'update'])->name('student.update');

// Rota DELETE para eliminar um estudante da base de dados por ID
Route::delete('/student/destroy/{id}', [studentController::class, 'destroy'])->name('student.destroy');

/*
|--------------------------------------------------------------------------
| Rotas do Módulo de Formadores (Teacher/Formador CRUD)
|--------------------------------------------------------------------------
|
| Gestão completa de formadores: Listar, Criar, Salvar, Ver, Editar, Atualizar e Eliminar.
|
*/

// Rota GET para a listagem de todos os formadores registados
Route::get('/teacher/index', [teacherController::class, 'index'])->name('teacher.index');

// Rota GET para apresentar o formulário de registo de novo formador
Route::get('/teacher/create', [teacherController::class, 'create'])->name('teacher.create');

// Rota POST para processar a gravação dos dados do novo formador
Route::post('/teacher/store', [teacherController::class, 'store'])->name('teacher.store');

// Rota GET para visualizar os detalhes de um formador específico por ID
Route::get('/teacher/{id}', [teacherController::class, 'show'])->name('teacher.show');

// Rota GET para apresentar o formulário de edição de um formador existente por ID
Route::get('/teacher/edit/{id}', [teacherController::class, 'edit'])->name('teacher.edit');

// Rota PUT para processar a atualização dos dados do formador por ID
Route::put('/teacher/update/{id}', [teacherController::class, 'update'])->name('teacher.update');

// Rota DELETE para eliminar um formador da base de dados por ID
Route::delete('/teacher/destroy/{id}', [teacherController::class, 'destroy'])->name('teacher.destroy');

/*
|--------------------------------------------------------------------------
| Rotas do Módulo de pagamentos (Finance/Pagamento CRUD)
|--------------------------------------------------------------------------
|
| Gestão completa de pagamentos: Listar, Criar, Salvar, Ver, Editar, Atualizar e Eliminar.
|
*/

// Rota GET para a listagem de todos os pagamentos registados
Route::get('/payment/index', [paymentController::class, 'index'])->name('payment.index');

// Rota GET para apresentar o formulário de registo de novo pagamento
Route::get('/payment/create', [paymentController::class, 'create'])->name('payment.create');

// Rota POST para processar a gravação dos dados do novo pagamento
Route::post('/payment/store', [paymentController::class, 'store'])->name('payment.store');

// Rota GET para visualizar os detalhes de um pagamento específico por ID
Route::get('/payment/{id}', [paymentController::class, 'show'])->name('payment.show');

// Rota GET para apresentar o formulário de edição de um pagamento existente por ID
Route::get('/payment/edit/{id}', [paymentController::class, 'edit'])->name('payment.edit');

// Rota PUT para processar a atualização dos dados do pagamento por ID
Route::put('/payment/update/{id}', [paymentController::class, 'update'])->name('payment.update');

// Rota DELETE para eliminar um pagamento da base de dados por ID
Route::delete('/payment/destroy/{id}', [paymentController::class, 'destroy'])->name('payment.destroy');

/*
|--------------------------------------------------------------------------
| Rotas do Módulo de Turmas (Class/Turma CRUD)
|--------------------------------------------------------------------------
|
| Gestão completa de turmas: Listar, Criar, Salvar, Ver, Editar, Atualizar e Eliminar.
|
*/

// Rota GET para a listagem de todas as turmas registadas
Route::get('/class/index', [classController::class, 'index'])->name('class.index');

// Rota GET para apresentar o formulário de registo de nova turma
Route::get('/class/create', [classController::class, 'create'])->name('class.create');

// Rota POST para processar a gravação dos dados da nova turma
Route::post('/class/store', [classController::class, 'store'])->name('class.store');

// Rota GET para visualizar os detalhes de uma turma específica por ID
Route::get('/class/{id}', [classController::class, 'show'])->name('class.show');

// Rota GET para apresentar o formulário de edição de uma turma existente por ID
Route::get('/class/edit/{id}', [classController::class, 'edit'])->name('class.edit');

// Rota PUT para processar a atualização dos dados da turma por ID
Route::put('/class/update/{id}', [classController::class, 'update'])->name('class.update');

// Rota DELETE para eliminar uma turma da base de dados por ID
Route::delete('/class/destroy/{id}', [classController::class, 'destroy'])->name('class.destroy');