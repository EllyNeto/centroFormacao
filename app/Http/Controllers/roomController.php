<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Room;
use Illuminate\Http\Request;

/**
 * Controlador RESTful para gerir turmas (rooms) da instituição de ensino.
 *
 * Implementa os sete métodos de recurso padrão do Laravel:
 * index, create, store, show, edit, update e destroy.
 *
 * Uma turma está associada a um docente, a um curso e opcionalmente a uma
 * sala de aula (classroom), através de chaves estrangeiras.
 */
class roomController extends Controller
{
    /**
     * Lista todas as turmas com os relacionamentos docente, curso e sala carregados.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        // with() carrega antecipadamente (eager loading) os relacionamentos
        // teacher, course e classroom, evitando consultas N+1.
        return Room::with(['teacher', 'course', 'classroom'])->get();
    }

    /**
     * Exibe o formulário para criar uma nova turma (stub).
     *
     * Neste projeto a criação é feita por API, sem view de formulário Blade.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Armazena uma nova turma na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os dados da turma
     * @return \App\Models\Room
     */
    public function store(Request $request)
    {
        // Valida os dados de entrada da turma:
        // name (string), start_time e end_time (formato H:i, com end_time depois de start_time),
        // days_of_week (array), shift (Manhã/Tarde/Pós-Laboral), max_capacity (inteiro),
        // teacher_id e course_id (chaves estrangeiras existentes) e classroom_id (opcional).
        $validated = $request->validate([
            'name' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'days_of_week' => 'required|array',
            'shift' => 'required|in:Manhã,Tarde,Pós-Laboral',
            'max_capacity' => 'required|integer',
            'teacher_id' => 'required|exists:teachers,id',
            'course_id' => 'required|exists:courses,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
        ]);

        // Cria o registo da turma com os dados validados.
        $room = Room::create($validated);

        // Se a turma estiver ligada a uma sala de aula, herdamos a capacidade da sala
        // (classroom->capacity) caso não tenha sido informada uma capacidade diferente.
        $room->max_capacity = $room->classroom->capacity ?? $validated['max_capacity'];
        $room->save();

        return $room;
    }

    /**
     * Exibe os detalhes de uma turma específica com os seus relacionamentos.
     *
     * O modelo é injetado automaticamente pelo route model binding do Laravel.
     *
     * @param  \App\Models\Room  $room Turma a ser exibida
     * @return \App\Models\Room
     */
    public function show(Room $room)
    {
        // Carrega antecipadamente os relacionamentos teacher, course e classroom.
        return $room->load(['teacher', 'course', 'classroom']);
    }

    /**
     * Exibe o formulário para editar uma turma existente (stub).
     *
     * @param  \App\Models\Room  $room Turma a ser editada
     * @return \App\Models\Room
     */
    public function edit(Room $room)
    {
        // Retorna o recurso para edição (stub — sem view de formulário neste projeto).
        return $room;
    }

    /**
     * Atualiza os dados de uma turma existente.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os novos dados
     * @param  \App\Models\Room  $room Turma a ser atualizada
     * @return \App\Models\Room
     */
    public function update(Request $request, Room $room)
    {
        // sometimes| aplica cada regra apenas quando o campo estiver presente,
        // permitindo atualizações parciais da turma.
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i',
            'days_of_week' => 'sometimes|array',
            'shift' => 'sometimes|in:Manhã,Tarde,Pós-Laboral',
            'max_capacity' => 'sometimes|integer',
            'teacher_id' => 'sometimes|exists:teachers,id',
            'course_id' => 'sometimes|exists:courses,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
        ]);

        // Se o campo classroom_id foi enviado, atualizamos a capacidade da turma com
        // a capacidade da nova sala de aula associada (ou mantemos a capacidade atual).
        if (isset($validated['classroom_id'])) {
            $validated['max_capacity'] = Classroom::find($validated['classroom_id'])?->capacity ?? $validated['max_capacity'] ?? $room->max_capacity;
        }

        // Persiste as alterações apenas nos campos enviados na requisição.
        $room->update($validated);

        return $room;
    }

    /**
     * Remove uma turma da base de dados.
     *
     * Com o trait SoftDeletes no modelo, a remoção é lógica (coluna deleted_at).
     *
     * @param  \App\Models\Room  $room Turma a ser removida
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return response()->noContent();
    }
}
