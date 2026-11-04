<?php

use App\Http\Controllers\classroomController;
use App\Http\Controllers\courseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\enrollmentController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\roomController;
use App\Http\Controllers\studentController;
use App\Http\Controllers\teacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Web da Aplicação — Centro de Formação
|--------------------------------------------------------------------------
|
| Aqui são registadas todas as rotas web do sistema de gestão integrada.
| As rotas estão organizadas por módulos operacionais e mapeadas para os seus
| respetivos controladores utilizando convenções RESTful padrão do Laravel.
|
*/

// =========================================================================
// 1. Painel Principal (Dashboard Executivo e Analítico)
// =========================================================================

/**
 * Rota da página inicial / painel de controlo.
 * Carrega indicadores em tempo real, gráficos de desempenho e atalhos operacionais.
 */
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// =========================================================================
// 2. Módulo de Cursos (Oferta Formativa)
// =========================================================================

/**
 * Rotas RESTful para a gestão de cursos.
 * Mapeia as ações: index, create, store, show, edit, update e destroy.
 * URI base: /curso | Nome das rotas: course.*
 */
Route::resource('curso', courseController::class)->names('course')->parameters(['curso' => 'course']);

// Rotas amigáveis de atalho (conforme a especificação em ESTRUTURA_DO_PROJETO.md)
Route::prefix('curso')->group(function () {
    Route::get('/listar', [courseController::class, 'index'])->name('course.list.alias');
    Route::get('/adicionar', [courseController::class, 'create'])->name('course.create.alias');
    Route::get('/edit/{course}', [courseController::class, 'edit'])->name('course.edit.alias');
    Route::put('/update/{course}', [courseController::class, 'update'])->name('course.update.alias');
});

// =========================================================================
// 3. Módulo de Formadores (Corpo Docente)
// =========================================================================

/**
 * Rotas RESTful para o corpo docente / formadores.
 * URI base: /formador | Nome das rotas: teacher.*
 */
Route::resource('formador', teacherController::class)->names('teacher')->parameters(['formador' => 'teacher']);

Route::prefix('formador')->group(function () {
    Route::get('/listar', [teacherController::class, 'index'])->name('teacher.list.alias');
    Route::get('/adicionar', [teacherController::class, 'create'])->name('teacher.create.alias');
    Route::get('/edit/{teacher}', [teacherController::class, 'edit'])->name('teacher.edit.alias');
    Route::put('/update/{teacher}', [teacherController::class, 'update'])->name('teacher.update.alias');
});

// =========================================================================
// 4. Módulo de Salas de Aula (Infraestrutura Física)
// =========================================================================

/**
 * Rotas RESTful para as salas de aula físicas e controlo de capacidade.
 * URI base: /sala | Nome das rotas: classroom.*
 */
Route::resource('sala', classroomController::class)->names('classroom')->parameters(['sala' => 'classroom']);

Route::prefix('sala')->group(function () {
    Route::get('/listar', [classroomController::class, 'index'])->name('classroom.list.alias');
    Route::get('/adicionar', [classroomController::class, 'create'])->name('classroom.create.alias');
    Route::get('/edit/{classroom}', [classroomController::class, 'edit'])->name('classroom.edit.alias');
    Route::put('/update/{classroom}', [classroomController::class, 'update'])->name('classroom.update.alias');
});

// =========================================================================
// 5. Módulo de Turmas (Gestão Pedagógica / Alocação)
// =========================================================================

/**
 * Rotas RESTful para turmas (associação de curso, formador, sala, horário e turno).
 * URI base: /turma | Nome das rotas: room.*
 */
Route::resource('turma', roomController::class)->names('room')->parameters(['turma' => 'room']);

Route::prefix('turma')->group(function () {
    Route::get('/listar', [roomController::class, 'index'])->name('room.list.alias');
    Route::get('/adicionar', [roomController::class, 'create'])->name('room.create.alias');
    Route::get('/edit/{room}', [roomController::class, 'edit'])->name('room.edit.alias');
    Route::put('/update/{room}', [roomController::class, 'update'])->name('room.update.alias');
});

// =========================================================================
// 6. Módulo de Inscrições / Candidaturas
// =========================================================================

/**
 * Rotas RESTful para receção e triagem de candidaturas a cursos do centro.
 * URI base: /inscricao | Nome das rotas: enrollment.*
 */
Route::resource('inscricao', enrollmentController::class)->names('enrollment')->parameters(['inscricao' => 'enrollment']);

Route::prefix('inscricao')->group(function () {
    Route::get('/listar', [enrollmentController::class, 'index'])->name('enrollment.list.alias');
    Route::get('/adicionar', [enrollmentController::class, 'create'])->name('enrollment.create.alias');
    Route::get('/edit/{enrollment}', [enrollmentController::class, 'edit'])->name('enrollment.edit.alias');
    Route::put('/update/{enrollment}', [enrollmentController::class, 'update'])->name('enrollment.update.alias');
});

// =========================================================================
// 7. Módulo de Pagamentos (Liquidação Financeira e Emissão de Recibos)
// =========================================================================

/**
 * Rotas RESTful para liquidação financeira de inscrições em Kwanzas (AOA).
 * URI base: /pagamento | Nome das rotas: payment.*
 */
Route::resource('pagamento', paymentController::class)->names('payment')->parameters(['pagamento' => 'payment']);

Route::prefix('pagamento')->group(function () {
    Route::get('/listar', [paymentController::class, 'index'])->name('payment.list.alias');
    Route::get('/adicionar', [paymentController::class, 'create'])->name('payment.create.alias');
    Route::get('/edit/{payment}', [paymentController::class, 'edit'])->name('payment.edit.alias');
    Route::put('/update/{payment}', [paymentController::class, 'update'])->name('payment.update.alias');
});

// =========================================================================
// 8. Módulo de Formandos (Alunos Matriculados Oficiais)
// =========================================================================

/**
 * Rotas RESTful para visualização e acompanhamento de formandos oficiais.
 * URI base: /formando | Nome das rotas: student.*
 */
Route::resource('formando', studentController::class)->names('student')->parameters(['formando' => 'student']);

Route::prefix('formando')->group(function () {
    Route::get('/listar', [studentController::class, 'index'])->name('student.list.alias');
    Route::get('/adicionar', [studentController::class, 'create'])->name('student.create.alias');
    Route::get('/edit/{student}', [studentController::class, 'edit'])->name('student.edit.alias');
    Route::put('/update/{student}', [studentController::class, 'update'])->name('student.update.alias');
});
