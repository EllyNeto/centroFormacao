<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\_Class;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Student;

/**
 * Controlador responsável pela gestão dinâmica e completa das operações CRUD da entidade Turma (_Class).
 */
class classController extends Controller
{
    /**
     * Exibe a listagem dinâmica de todas as turmas registadas na base de dados.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $classes = _Class::with(['course', 'teacher', 'student'])->orderBy('id', 'desc')->get();
        return view('admin.room.list.index', ['classes' => $classes]);
    }

    /**
     * Exibe o formulário para registar uma nova turma com cursos, formadores e estudantes.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $courses = Course::where('status', 1)->get();
        $teachers = Teacher::where('status', 1)->get();
        $students = Student::all();

        return view('admin.room.create.index', [
            'courses'  => $courses,
            'teachers' => $teachers,
            'students' => $students,
        ]);
    }

    /**
     * Valida os dados submetidos pelo formulário e guarda uma nova turma na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'days_of_week' => 'required',
            'code'         => 'nullable|string|max:100',
            'shift'        => 'required|string|max:50',
            'capacity'     => 'required|integer|min:1',
            'status'       => 'required|boolean',
            'teacher_id'   => 'required|exists:teachers,id',
            'course_id'    => 'required|exists:courses,id',
            'student_id'   => 'nullable|exists:students,id',
            'falta'        => 'nullable|integer|min:0',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i',
        ], [
            'name.required'       => 'O nome da turma é obrigatório.',
            'shift.required'      => 'Por favor escolha o turno da turma.',
            'capacity.required'   => 'A capacidade da turma é obrigatória.',
            'status.required'     => 'Por favor selecione o estado da turma.',
            'days_of_week'        => 'Selecione algum dia da semana.',
            'teacher_id.required' => 'Por favor, selecione um formador/professor.',
            'teacher_id.exists'   => 'O formador selecionado não existe.',
            'course_id.required'  => 'Por favor, selecione um curso associado.',
            'course_id.exists'    => 'O curso selecionado não existe.',
        ]);

        if (empty($validatedData['code'])) {
            $validatedData['code'] = 'TURMA-' . date('Y') . '-' . rand(100, 999);
        }

        if (!isset($validatedData['falta'])) {
            $validatedData['falta'] = 0;
        }

        _Class::create($validatedData);

        return redirect()->route('class.index')->with('success', 'Turma registada com sucesso!');
    }

    /**
     * Exibe a página de detalhes de uma turma específica pelo ID.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $class = _Class::with(['course', 'teacher', 'student'])->findOrFail($id);
        return view('admin.room.details.index', ['class' => $class]);
    }

    /**
     * Exibe o formulário de edição para alterar os dados de uma turma existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $class = _Class::findOrFail($id);
        $courses = Course::where('status', 1)->get();
        $teachers = Teacher::where('status', 1)->get();
        $students = Student::all();

        return view('admin.room.edit.index', [
            'class'    => $class,
            'courses'  => $courses,
            'teachers' => $teachers,
            'students' => $students,
        ]);
    }

    /**
     * Valida e atualiza os dados de uma turma existente na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $class = _Class::findOrFail($id);

        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'days_of_week' => 'required',
            'code'         => 'nullable|string|max:100',
            'shift'        => 'required|string|max:50',
            'capacity'     => 'required|integer|min:1',
            'status'       => 'required|boolean',
            'teacher_id'   => 'required|exists:teachers,id',
            'course_id'    => 'required|exists:courses,id',
            'student_id'   => 'nullable|exists:students,id',
            'falta'        => 'nullable|integer|min:0',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i',
        ], [
            'name.required'       => 'O nome da turma é obrigatório.',
            'shift.required'      => 'Por favor escolha o turno da turma.',
            'capacity.required'   => 'A capacidade da turma é obrigatória.',
            'status.required'     => 'Por favor selecione o estado da turma.',
            'days_of_week'        => 'Selecione algum dia da semana.',
            'teacher_id.required' => 'Por favor, selecione um formador/professor.',
            'teacher_id.exists'   => 'O formador selecionado não existe.',
            'course_id.required'  => 'Por favor, selecione um curso associado.',
            'course_id.exists'    => 'O curso selecionado não existe.',
        ]);

        if (!isset($validatedData['falta'])) {
            $validatedData['falta'] = 0;
        }

        // Garante que o código da turma não seja modificado na atualização
        $validatedData['code'] = $class->code;

        $class->update($validatedData);

        return redirect()->route('class.index')->with('success', 'Turma atualizada com sucesso!');
    }

    /**
     * Remove uma turma da base de dados (utilizando SoftDeletes).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $class = _Class::findOrFail($id);
        $class->delete();

        return redirect()->route('class.index')->with('success', 'Turma eliminada com sucesso!');
    }
}
