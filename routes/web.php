<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\courseController;
use App\Http\Controllers\studentController;
use App\Http\Controllers\teacherController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\classController;
use App\Http\Controllers\enrollmentController;
use App\Http\Controllers\invoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Rotas de Autenticação para Convidados (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rotas Protegidas por Autenticação (Auth)
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return view('admin.dashboard.index');
    });

    Route::get('/dashboard/main', function () {
        return view('admin.dashboard.index');
    })->name('dashboard.main');

    /*
    |--------------------------------------------------------------------------
    | Rotas do Módulo de Cursos (Course CRUD)
    |--------------------------------------------------------------------------
    */
    Route::get('/course/index', [courseController::class, 'index'])->name('course.index');
    Route::get('/course/create', [courseController::class, 'create'])->name('course.create');
    Route::post('/course/store', [courseController::class, 'store'])->name('course.store');
    Route::get('/course/{id}', [courseController::class, 'show'])->name('course.show');
    Route::get('/course/edit/{id}', [courseController::class, 'edit'])->name('course.edit');
    Route::put('/course/update/{id}', [courseController::class, 'update'])->name('course.update');
    Route::delete('/course/destroy/{id}', [courseController::class, 'destroy'])->name('course.destroy');

    /*
    |--------------------------------------------------------------------------
    | Rotas do Módulo de Estudantes (Student CRUD)
    |--------------------------------------------------------------------------
    */
    Route::get('/student/index', [studentController::class, 'index'])->name('student.index');
    Route::get('/student/create', [studentController::class, 'create'])->name('student.create');
    Route::post('/student/store', [studentController::class, 'store'])->name('student.store');
    Route::get('/student/{id}', [studentController::class, 'show'])->name('student.show');
    Route::get('/student/edit/{id}', [studentController::class, 'edit'])->name('student.edit');
    Route::put('/student/update/{id}', [studentController::class, 'update'])->name('student.update');
    Route::delete('/student/destroy/{id}', [studentController::class, 'destroy'])->name('student.destroy');

    /*
    |--------------------------------------------------------------------------
    | Rotas do Módulo de Formadores (Teacher/Formador CRUD)
    |--------------------------------------------------------------------------
    */
    Route::get('/teacher/index', [teacherController::class, 'index'])->name('teacher.index');
    Route::get('/teacher/create', [teacherController::class, 'create'])->name('teacher.create');
    Route::post('/teacher/store', [teacherController::class, 'store'])->name('teacher.store');
    Route::get('/teacher/{id}', [teacherController::class, 'show'])->name('teacher.show');
    Route::get('/teacher/edit/{id}', [teacherController::class, 'edit'])->name('teacher.edit');
    Route::put('/teacher/update/{id}', [teacherController::class, 'update'])->name('teacher.update');
    Route::delete('/teacher/destroy/{id}', [teacherController::class, 'destroy'])->name('teacher.destroy');

    /*
    |--------------------------------------------------------------------------
    | Rotas do Módulo de Pagamentos (Finance/Pagamento CRUD)
    |--------------------------------------------------------------------------
    */
    Route::get('/payment/index', [paymentController::class, 'index'])->name('payment.index');
    Route::get('/payment/create', [paymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/store', [paymentController::class, 'store'])->name('payment.store');
    Route::get('/payment/{id}', [paymentController::class, 'show'])->name('payment.show');
    Route::get('/payment/edit/{id}', [paymentController::class, 'edit'])->name('payment.edit');
    Route::put('/payment/update/{id}', [paymentController::class, 'update'])->name('payment.update');
    Route::delete('/payment/destroy/{id}', [paymentController::class, 'destroy'])->name('payment.destroy');

    /*
    |--------------------------------------------------------------------------
    | Rotas do Módulo de Turmas (Class/Turma CRUD)
    |--------------------------------------------------------------------------
    */
    Route::get('/class/index', [classController::class, 'index'])->name('class.index');
    Route::get('/class/create', [classController::class, 'create'])->name('class.create');
    Route::post('/class/store', [classController::class, 'store'])->name('class.store');
    Route::get('/class/{id}', [classController::class, 'show'])->name('class.show');
    Route::get('/class/edit/{id}', [classController::class, 'edit'])->name('class.edit');
    Route::put('/class/update/{id}', [classController::class, 'update'])->name('class.update');
    Route::delete('/class/destroy/{id}', [classController::class, 'destroy'])->name('class.destroy');

    /*
    |--------------------------------------------------------------------------
    | Rotas do Módulo de Inscrições (Enrollment/Inscrição CRUD)
    |--------------------------------------------------------------------------
    */
    Route::get('/enrollment/index', [enrollmentController::class, 'index'])->name('enrollment.index');
    Route::get('/enrollment/create', [enrollmentController::class, 'create'])->name('enrollment.create');
    Route::post('/enrollment/store', [enrollmentController::class, 'store'])->name('enrollment.store');
    Route::get('/enrollment/{id}', [enrollmentController::class, 'show'])->name('enrollment.show');
    Route::get('/enrollment/edit/{id}', [enrollmentController::class, 'edit'])->name('enrollment.edit');
    Route::put('/enrollment/update/{id}', [enrollmentController::class, 'update'])->name('enrollment.update');
    Route::delete('/enrollment/destroy/{id}', [enrollmentController::class, 'destroy'])->name('enrollment.destroy');

    /*
    |--------------------------------------------------------------------------
    | Rotas do Módulo de Faturas (Invoice/Fatura CRUD)
    |--------------------------------------------------------------------------
    */
    Route::get('/invoice/index', [invoiceController::class, 'index'])->name('invoice.index');
    Route::get('/invoice/create', [invoiceController::class, 'create'])->name('invoice.create');
    Route::post('/invoice/store', [invoiceController::class, 'store'])->name('invoice.store');
    Route::get('/invoice/{id}', [invoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/edit/{id}', [invoiceController::class, 'edit'])->name('invoice.edit');
    Route::put('/invoice/update/{id}', [invoiceController::class, 'update'])->name('invoice.update');
    Route::delete('/invoice/destroy/{id}', [invoiceController::class, 'destroy'])->name('invoice.destroy');
});