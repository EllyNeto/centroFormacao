<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação (Apenas Login para Visitantes)
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/contact', [AuthController::class, 'showContactForm'])->name('contact');
    Route::post('/lead', [AuthController::class, 'storeLead'])->name('lead.store');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas da Aplicação (Requerem Autenticação)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Terminar Sessão (Logout)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Página Principal (Dashboard com Indicadores Reais)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Rotas de Gestão de Utilizadores (Registo exclusivo pelo Admin dentro do sistema)
    Route::get('/utilizador/listar', [AuthController::class, 'index'])->name('user.index');
    Route::get('/utilizador/adicionar', [AuthController::class, 'create'])->name('user.create');
    Route::post('/utilizador', [AuthController::class, 'store'])->name('user.store');
    Route::delete('/utilizador/{id}', [AuthController::class, 'destroy'])->name('user.destroy');

    // Rotas de Gestão de Cursos
    Route::get('/curso/listar', [CourseController::class, 'index'])->name('course.index');
    Route::get('/curso/adicionar', [CourseController::class, 'create'])->name('course.create');
    Route::post('/curso', [CourseController::class, 'store'])->name('course.store');
    Route::get('/curso/{id}', [CourseController::class, 'show'])->name('course.show');
    Route::get('/curso/edit/{id}', [CourseController::class, 'edit'])->name('course.edit');
    Route::put('/curso/update/{id}', [CourseController::class, 'update'])->name('course.update');
    Route::delete('/curso/{id}', [CourseController::class, 'destroy'])->name('course.destroy');

    // Rotas de Gestão de Formadores (Professores)
    Route::get('/formador/listar', [TeacherController::class, 'index'])->name('teacher.index');
    Route::get('/formador/adicionar', [TeacherController::class, 'create'])->name('teacher.create');
    Route::post('/formador', [TeacherController::class, 'store'])->name('teacher.store');
    Route::get('/formador/{id}', [TeacherController::class, 'show'])->name('teacher.show');
    Route::get('/formador/edit/{id}', [TeacherController::class, 'edit'])->name('teacher.edit');
    Route::put('/formador/update/{id}', [TeacherController::class, 'update'])->name('teacher.update');
    Route::delete('/formador/{id}', [TeacherController::class, 'destroy'])->name('teacher.destroy');

    // Rotas de Gestão de Salas
    Route::get('/sala/listar', [ClassroomController::class, 'index'])->name('classroom.index');
    Route::get('/sala/adicionar', [ClassroomController::class, 'create'])->name('classroom.create');
    Route::post('/sala', [ClassroomController::class, 'store'])->name('classroom.store');
    Route::get('/sala/{id}', [ClassroomController::class, 'show'])->name('classroom.show');
    Route::get('/sala/edit/{id}', [ClassroomController::class, 'edit'])->name('classroom.edit');
    Route::put('/sala/update/{id}', [ClassroomController::class, 'update'])->name('classroom.update');
    Route::delete('/sala/{id}', [ClassroomController::class, 'destroy'])->name('classroom.destroy');

    // Rotas de Gestão de Turmas
    Route::get('/turma/listar', [RoomController::class, 'index'])->name('room.index');
    Route::get('/turma/adicionar', [RoomController::class, 'create'])->name('room.create');
    Route::post('/turma', [RoomController::class, 'store'])->name('room.store');
    Route::get('/turma/{id}', [RoomController::class, 'show'])->name('room.show');
    Route::get('/turma/{id}/imprimir-formandos', [RoomController::class, 'printStudents'])->name('room.printStudents');
    Route::get('/turma/edit/{id}', [RoomController::class, 'edit'])->name('room.edit');
    Route::put('/turma/update/{id}', [RoomController::class, 'update'])->name('room.update');
    Route::delete('/turma/{id}', [RoomController::class, 'destroy'])->name('room.destroy');

    // Rotas de Gestão de Formandos (Alunos)
    Route::get('/formando/listar', [StudentController::class, 'index'])->name('student.index');
    Route::get('/formando/adicionar', [StudentController::class, 'create'])->name('student.create');
    Route::post('/formando', [StudentController::class, 'store'])->name('student.store');
    Route::get('/formando/{id}', [StudentController::class, 'show'])->name('student.show');
    Route::get('/formando/edit/{id}', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('/formando/update/{id}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('/formando/{id}', [StudentController::class, 'destroy'])->name('student.destroy');

    // Rotas de Gestão de Inscrições / Matrículas
    Route::get('/inscricao/listar', [EnrollmentController::class, 'index'])->name('enrollment.index');
    Route::get('/inscricao/adicionar', [EnrollmentController::class, 'create'])->name('enrollment.create');
    Route::post('/inscricao', [EnrollmentController::class, 'store'])->name('enrollment.store');
    Route::get('/inscricao/{id}', [EnrollmentController::class, 'show'])->name('enrollment.show');
    Route::get('/inscricao/edit/{id}', [EnrollmentController::class, 'edit'])->name('enrollment.edit');
    Route::put('/inscricao/update/{id}', [EnrollmentController::class, 'update'])->name('enrollment.update');
    Route::delete('/inscricao/{id}', [EnrollmentController::class, 'destroy'])->name('enrollment.destroy');
    // Ação específica: efetivar inscrição com propina paga em formando oficial
    Route::post('/inscricao/{id}/efectivar', [StudentController::class, 'storeFromEnrollment'])->name('enrollment.enroll');

    // Rotas de Gestão de Pagamentos
    Route::get('/pagamento/listar', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/pagamento/adicionar', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/pagamento', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/pagamento/{id}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('/pagamento/edit/{id}', [PaymentController::class, 'edit'])->name('payment.edit');
    Route::put('/pagamento/update/{id}', [PaymentController::class, 'update'])->name('payment.update');
    Route::delete('/pagamento/{id}', [PaymentController::class, 'destroy'])->name('payment.destroy');
});
