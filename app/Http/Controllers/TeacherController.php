<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Models\Teacher;
use Illuminate\Http\Request;

/**
 * Controller TeacherController - Gestão de Formadores.
 *
 * Responsabilidades:
 * - CRUD completo para formadores
 * - Verificação de formadores ativos antes de exclusão
 */
class TeacherController extends Controller
{
    /**
     * Exibe a listagem de todos os formadores cadastrados.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $teachers = Teacher::withCount('rooms')
            ->orderBy('name')
            ->get();

        return view('admin.teacher.list.index', compact('teachers'));
    }

    /**
     * Exibe o formulário para criação de um novo formador.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.teacher.create.index');
    }

    /**
     * Valida e armazena um novo formador na base de dados.
     * 
     * @param  \App\Http\Requests\TeacherRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TeacherRequest $request)
    {
        // Dados validados pelo Form Request
        $validatedData = $request->validated();

        // Cria o formador
        Teacher::create($validatedData);

        return redirect()
            ->route('teacher.index')
            ->with('success', 'Formador criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um formador específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $teacher = Teacher::with(['rooms.course', 'rooms.classroom'])
            ->findOrFail($id);

        return view('admin.teacher.details.index', compact('teacher'));
    }

    /**
     * Exibe o formulário para edição de um formador existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('admin.teacher.edit.index', compact('teacher'));
    }

    /**
     * Valida e atualiza um formador existente.
     * 
     * @param  \App\Http\Requests\TeacherRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TeacherRequest $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $validatedData = $request->validated();

        $teacher->update($validatedData);

        return redirect()
            ->route('teacher.index')
            ->with('success', 'Formador atualizado com sucesso!');
    }

    /**
     * Remove um formador (Soft Delete).
     * Impede exclusão se houver turmas ativas vinculadas.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);

        if ($teacher->hasActiveRooms()) {
            return redirect()
                ->route('teacher.index')
                ->with('error', 'Não é possível excluir este formador pois ele possui turmas ativas vinculadas.');
        }

        $teacher->delete();

        return redirect()
            ->route('teacher.index')
            ->with('success', 'Formador excluído com sucesso!');
    }
}
