<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;

class enrollmentController extends Controller
{
    /**
     * Exibe a listagem de todas as inscrições registadas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $enrollments = Enrollment::with(['student', 'course'])->orderBy('id', 'desc')->get();
        return view('admin.enrollment.list.index', ['enrollments' => $enrollments]);
    }

    /**
     * Exibe o formulário de registo de nova inscrição.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $students = Student::all();
        $courses  = Course::where('status', 1)->get();
        return view('admin.enrollment.create.index', [
            'students' => $students,
            'courses'  => $courses,
        ]);
    }

    /**
     * Valida e salva uma nova inscrição na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id'  => 'required|exists:courses,id',
            'date'        => 'required|date',
            'status'      => 'required|boolean',
        ], [
            'student_id.required' => 'Por favor selecione ou pesquise o estudante.',
            'student_id.exists'   => 'O estudante selecionado é inválido.',
            'course_id.required'  => 'Por favor selecione ou pesquise o curso.',
            'course_id.exists'    => 'O curso selecionado é inválido.',
            'date.required'       => 'A data da inscrição é obrigatória.',
            'status.required'     => 'Selecione o estado da inscrição.',
        ]);

        Enrollment::create($validatedData);

        return redirect()->route('enrollment.index')->with('success', 'Inscrição efetuada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma inscrição específica.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $enrollment = Enrollment::with(['student', 'course'])->findOrFail($id);
        return view('admin.enrollment.details.index', ['enrollment' => $enrollment]);
    }

    /**
     * Exibe o formulário para editar uma inscrição existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $students = Student::all();
        $courses  = Course::where('status', 1)->get();

        return view('admin.enrollment.edit.index', [
            'enrollment' => $enrollment,
            'students'   => $students,
            'courses'    => $courses,
        ]);
    }

    /**
     * Valida e atualiza uma inscrição na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id'  => 'required|exists:courses,id',
            'status'      => 'required|boolean',
        ], [
            'student_id.required' => 'Por favor selecione ou pesquise o estudante.',
            'student_id.exists'   => 'O estudante selecionado é inválido.',
            'course_id.required'  => 'Por favor selecione ou pesquise o curso.',
            'course_id.exists'    => 'O curso selecionado é inválido.',
            'status.required'     => 'Selecione o estado da inscrição.',
        ]);

        // Impede alteração da data original de inscrição
        $validatedData['date'] = $enrollment->date;

        $enrollment->update($validatedData);

        return redirect()->route('enrollment.index')->with('success', 'Inscrição atualizada com sucesso!');
    }

    /**
     * Remove uma inscrição da base de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return redirect()->route('enrollment.index')->with('success', 'Inscrição eliminada com sucesso!');
    }
}
