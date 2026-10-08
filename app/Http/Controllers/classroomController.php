<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class classroomController extends Controller
{
    /**
     * Exibe a listagem de salas.
     */
    public function index()
    {
        $classrooms = Classroom::withCount('rooms')->latest()->get();
        return view('admin.classroom.list.index', compact('classrooms'));
    }

    /**
     * Exibe o formulário de cadastro de nova sala.
     */
    public function create()
    {
        return view('admin.classroom.create.index');
    }

    /**
     * Valida e armazena uma nova sala na base de dados.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'                 => 'nullable|string|max:255',
            'number_of_classroom'  => 'required|integer|min:1',
            'capacity'             => 'required|integer|min:1',
            'description'          => 'nullable|string',
        ], [
            'name.required'                => 'O nome da sala é obrigatório.',
            'number_of_classroom.required' => 'O número da sala é obrigatório.',
            'number_of_classroom.integer'  => 'O número da sala deve ser um valor inteiro.',
            'capacity.integer'             => 'A capacidade deve ser um valor inteiro.',
        ]);

        Classroom::create($validatedData);

        return redirect()->route('classroom.index')->with('success', 'Sala criada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma sala específica.
     */
    public function show($id)
    {
        $classroom = Classroom::with(['rooms.teacher', 'rooms.course'])->findOrFail($id);
        return view('admin.classroom.detail.index', compact('classroom'));
    }

    /**
     * Exibe o formulário de edição de uma sala.
     */
    public function edit($id)
    {
        $classroom = Classroom::findOrFail($id);
        return view('admin.classroom.edit.index', compact('classroom'));
    }

    /**
     * Atualiza os dados de uma sala na base de dados.
     */
    public function update(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);

        $validatedData = $request->validate([
            'name'                 => 'nullable|string|max:255',
            'number_of_classroom'  => 'required|integer|min:1',
            'capacity'             => 'required|integer|min:1',
            'description'          => 'nullable|string',
        ], [
            'name.required'                => 'O nome da sala é obrigatório.',
            'number_of_classroom.required' => 'O número da sala é obrigatório.',
            'number_of_classroom.integer'  => 'O número da sala deve ser um valor inteiro.',
            'capacity.integer'             => 'A capacidade deve ser um valor inteiro.',
        ]);

        $classroom->update($validatedData);

        return redirect()->route('classroom.index')->with('success', 'Sala atualizada com sucesso!');
    }

    /**
     * Remove uma sala (Soft Delete).
     */
    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();

        return redirect()->route('classroom.index')->with('success', 'Sala eliminada com sucesso!');
    }
}
