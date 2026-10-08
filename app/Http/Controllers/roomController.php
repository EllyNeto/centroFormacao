<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;

class roomController extends Controller
{
    /**
     * Exibe a listagem de turmas.
     */
    public function index()
    {
        $rooms = Room::with(['teacher', 'course', 'classroom'])->latest()->get();
        return view('admin.room.list.index', compact('rooms'));
    }

    /**
     * Exibe o formulário de cadastro de nova turma.
     */
    public function create()
    {
        $teachers   = Teacher::all();
        $courses    = Course::all();
        $classrooms = Classroom::all();

        return view('admin.room.create.index', compact('teachers', 'courses', 'classrooms'));
    }

    /**
     * Valida e armazena uma nova turma na base de dados.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'start_time'   => 'required',
            'end_time'     => 'required',
            'days_of_week' => 'required|array',
            'shift'        => 'required|string|max:255',
            'teacher_id'   => 'required|exists:teachers,id',
            'course_id'    => 'required|exists:courses,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'max_capacity' => 'required',
        ], [
            'name.required'         => 'O nome da turma é obrigatório.',
            'start_time.required'   => 'A hora de início é obrigatória.',
            'end_time.required'     => 'A hora de término é obrigatória.',
            'days_of_week.required' => 'Selecione pelo menos um dia da semana.',
            'shift.required'        => 'O turno é obrigatório.',
            'teacher_id.required'   => 'Por favor, selecione um formador responsável.',
            'course_id.required'    => 'Por favor, selecione um curso associado.',
            'classroom_id.exists'   => 'A sala selecionada é inválida.',
        ]);

        if ($request->filled('classroom_id')) {
            $classroom = Classroom::find($request->classroom_id);
            if ($classroom && $classroom->capacity) {
                $validatedData['max_capacity'] = $classroom->capacity;
            }
        }

        Room::create($validatedData);

        return redirect()->route('room.index')->with('success', 'Turma criada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma turma específica.
     */
    public function show($id)
    {
        $room = Room::with(['teacher', 'course', 'classroom'])->findOrFail($id);
        return view('admin.room.details.index', compact('room'));
    }

    /**
     * Exibe o formulário de edição de uma turma.
     */
    public function edit($id)
    {
        $room       = Room::findOrFail($id);
        $teachers   = Teacher::all();
        $courses    = Course::all();
        $classrooms = Classroom::all();

        return view('admin.room.edit.index', compact('room', 'teachers', 'courses', 'classrooms'));
    }

    /**
     * Atualiza os dados de uma turma na base de dados.
     */
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'start_time'   => 'required',
            'end_time'     => 'required',
            'days_of_week' => 'required|array',
            'shift'        => 'required|string|max:255',
            'teacher_id'   => 'required|exists:teachers,id',
            'course_id'    => 'required|exists:courses,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'max_capacity' => 'required',
        ], [
            'name.required'         => 'O nome da turma é obrigatório.',
            'start_time.required'   => 'A hora de início é obrigatória.',
            'end_time.required'     => 'A hora de término é obrigatória.',
            'days_of_week.required' => 'Selecione pelo menos um dia da semana.',
            'shift.required'        => 'O turno é obrigatório.',
            'teacher_id.required'   => 'Por favor, selecione um formador responsável.',
            'course_id.required'    => 'Por favor, selecione um curso associado.',
            'classroom_id.exists'   => 'A sala selecionada é inválida.',
        ]);

        if ($request->filled('classroom_id')) {
            $classroom = Classroom::find($request->classroom_id);
            if ($classroom && $classroom->capacity) {
                $validatedData['max_capacity'] = $classroom->capacity;
            }
        }

        $room->update($validatedData);

        return redirect()->route('room.index')->with('success', 'Turma atualizada com sucesso!');
    }

    /**
     * Remove uma turma (Soft Delete).
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('room.index')->with('success', 'Turma eliminada com sucesso!');
    }
}
