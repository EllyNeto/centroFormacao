<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;

/**
 * Controller CourseController - Gestão de Cursos.
 *
 * Responsabilidades:
 * - CRUD completo para cursos
 * - Verificação de cursos ativos antes de exclusão/desativação
 */
class CourseController extends Controller
{
    /**
     * Exibe a listagem de todos os cursos cadastrados.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $courses = Course::withCount('enrollments', 'rooms')
            ->orderBy('name')
            ->get();

        return view('admin.course.list.index', compact('courses'));
    }

    /**
     * Exibe o formulário para criação de um novo curso.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.course.create.index');
    }

    /**
     * Valida e armazena um novo curso na base de dados.
     * 
     * @param  \App\Http\Requests\CourseRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CourseRequest $request)
    {
        // Dados validados pelo Form Request
        $validatedData = $request->validated();

        // Cria o curso
        Course::create($validatedData);

        return redirect()
            ->route('course.index')
            ->with('success', 'Curso criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um curso específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $course = Course::with(['enrollments', 'rooms.teacher', 'rooms.classroom'])
            ->findOrFail($id);

        return view('admin.course.details.index', compact('course'));
    }

    /**
     * Exibe o formulário para edição de um curso existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.course.edit.index', compact('course'));
    }

    /**
     * Valida e atualiza um curso existente.
     * 
     * @param  \App\Http\Requests\CourseRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CourseRequest $request, $id)
    {
        $course = Course::findOrFail($id);
        // Dados validados pelo Form Request
        $validatedData = $request->validated();

        $course->update($validatedData);

        return redirect()
            ->route('course.index')
            ->with('success', 'Curso atualizado com sucesso!');
    }

    /**
     * Remove um curso (Soft Delete).
     * Impede exclusão/desativação se houver turmas ou inscrições ativas.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        // Impede exclusão se o curso tiver turmas ou inscrições ativas para manter a integridade dos dados
        if ($course->hasActiveRooms() || $course->hasActiveEnrollments()) {
            return redirect()
                ->route('course.index')
                ->with('error', 'Não é possível excluir este curso pois ele possui turmas ou inscrições ativas vinculadas.');
        }

        $course->delete();

        return redirect()
            ->route('course.index')
            ->with('success', 'Curso excluído com sucesso!');
    }
}
