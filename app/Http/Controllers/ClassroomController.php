<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use Illuminate\Http\Request;

/**
 * Controller ClassroomController - Gestão de Salas de Aula.
 *
 * Responsabilidades:
 * - CRUD completo para salas de aula
 * - Verificação de salas ativas antes de exclusão
 */
class ClassroomController extends Controller
{
    /**
     * Exibe a listagem de todas as salas de aula cadastradas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $classrooms = Classroom::withCount('rooms')
            ->orderBy('number_of_classroom')
            ->get();

        return view('admin.classroom.list.index', compact('classrooms'));
    }

    /**
     * Exibe o formulário para criação de uma nova sala de aula.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.classroom.create.index');
    }

    /**
     * Valida e armazena uma nova sala de aula na base de dados.
     * 
     * @param  \App\Http\Requests\ClassroomRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ClassroomRequest $request)
    {
        // Dados validados pelo Form Request
        $validatedData = $request->validated();

        // Cria a sala de aula
        Classroom::create($validatedData);

        return redirect()
            ->route('classroom.index')
            ->with('success', 'Sala de aula criada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma sala de aula específica.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $classroom = Classroom::with(['rooms.course', 'rooms.teacher'])
            ->findOrFail($id);

        return view('admin.classroom.detail.index', compact('classroom'));
    }

    /**
     * Exibe o formulário para edição de uma sala de aula existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $classroom = Classroom::findOrFail($id);
        return view('admin.classroom.edit.index', compact('classroom'));
    }

    /**
     * Valida e atualiza uma sala de aula existente.
     * 
     * @param  \App\Http\Requests\ClassroomRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ClassroomRequest $request, $id)
    {
        $classroom = Classroom::findOrFail($id);
        $validatedData = $request->validated();

        $classroom->update($validatedData);

        return redirect()
            ->route('classroom.index')
            ->with('success', 'Sala de aula atualizada com sucesso!');
    }

    /**
     * Remove uma sala de aula (Soft Delete).
     * Impede exclusão se houver salas ativas vinculadas.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);

        if ($classroom->hasActiveRooms()) {
            return redirect()
                ->route('classroom.index')
                ->with('error', 'Não é possível excluir esta sala pois ela possui turmas ativas vinculadas.');
        }

        $classroom->delete();

        return redirect()
            ->route('classroom.index')
            ->with('success', 'Sala de aula excluída com sucesso!');
    }
}
