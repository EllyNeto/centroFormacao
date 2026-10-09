<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;

/**
 * Controlador roomController - Gestão de Turmas (Rooms).
 * Responsável pelas operações CRUD das turmas, validação estrita de choque de horários na Sala e no Formador,
 * e pela associação automática da capacidade máxima herdada da Sala de Aula física.
 */
class roomController extends Controller
{
    /**
     * Exibe a listagem de turmas cadastradas no sistema.
     * Carrega as relações de formador, curso e sala de aula física.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtém todas as turmas carregando os relacionamentos necessários
        $rooms = Room::with(['teacher', 'course', 'classroom'])
            ->latest()
            ->get();

        // Retorna a vista de listagem de turmas
        return view('admin.room.list.index', compact('rooms'));
    }

    /**
     * Exibe o formulário de cadastro de uma nova turma.
     * Carrega todos os formadores, cursos e salas disponíveis para seleção.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $teachers   = Teacher::orderBy('name', 'asc')->get();
        $courses    = Course::orderBy('name', 'asc')->get();
        $classrooms = Classroom::orderBy('number_of_classroom', 'asc')->get();

        // Retorna a vista do formulário de criação de turma
        return view('admin.room.create.index', compact('teachers', 'courses', 'classrooms'));
    }

    /**
     * Valida e armazena uma nova turma na base de dados.
     * Aplica verificações de choque de horário na Sala e no Formador.
     *
     * @param  \Illuminate\Http\Request  $request Dados recebidos do formulário
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validação básica dos campos recebidos
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'string',
            'shift'        => 'required|string|max:255',
            'teacher_id'   => 'required|exists:teachers,id',
            'course_id'    => 'required|exists:courses,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'max_capacity' => 'nullable|integer|min:1',
        ], [
            'name.required'         => 'O nome da turma é de preenchimento obrigatório.',
            'start_time.required'   => 'A hora de início das aulas é obrigatória.',
            'start_time.date_format'=> 'A hora de início deve estar no formato HH:MM.',
            'end_time.required'     => 'A hora de término das aulas é obrigatória.',
            'end_time.date_format'  => 'A hora de término deve estar no formato HH:MM.',
            'days_of_week.required' => 'Selecione pelo menos um dia da semana para as aulas.',
            'shift.required'        => 'O turno é de seleção obrigatória.',
            'teacher_id.required'   => 'Por favor, selecione o formador responsável.',
            'teacher_id.exists'     => 'O formador selecionado é inválido.',
            'course_id.required'    => 'Por favor, selecione o curso associado.',
            'course_id.exists'      => 'O curso selecionado é inválido.',
            'classroom_id.required' => 'Por favor, selecione a sala de aula física.',
            'classroom_id.exists'   => 'A sala de aula selecionada é inválida.',
        ]);

        // 2. REGRA DE NEGÓCIO 1: Validação Temporal básica (end_time > start_time)
        if (strtotime($request->end_time) <= strtotime($request->start_time)) {
            return back()
                ->withInput()
                ->withErrors(['end_time' => 'A hora de término deve ser posterior à hora de início.']);
        }

        // 3. REGRA DE NEGÓCIO 2: Verificar Conflito de Horário na Sala e no Formador
        $conflictError = $this->checkScheduleConflicts($request);
        if ($conflictError) {
            return back()->withInput()->withErrors($conflictError);
        }

        // 4. Atribuição Automática da Capacidade Herdada da Sala
        $classroom = Classroom::findOrFail($request->classroom_id);
        $validatedData['max_capacity'] = $classroom->capacity;

        // Cria a turma na base de dados
        Room::create($validatedData);

        // Redireciona com mensagem de sucesso
        return redirect()
            ->route('room.index')
            ->with('success', 'Turma criada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma turma específica.
     *
     * @param  int  $id Identificador primário da turma
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura a turma carregando as relações com formador, curso e sala
        $room = Room::with(['teacher', 'course', 'classroom'])->findOrFail($id);

        // Retorna a vista de detalhes da turma
        return view('admin.room.details.index', compact('room'));
    }

    /**
     * Exibe o formulário de edição de uma turma existente.
     *
     * @param  int  $id Identificador primário da turma
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $room       = Room::findOrFail($id);
        $teachers   = Teacher::orderBy('name', 'asc')->get();
        $courses    = Course::orderBy('name', 'asc')->get();
        $classrooms = Classroom::orderBy('number_of_classroom', 'asc')->get();

        // Retorna a vista do formulário de edição
        return view('admin.room.edit.index', compact('room', 'teachers', 'courses', 'classrooms'));
    }

    /**
     * Atualiza os dados de uma turma na base de dados.
     * Aplica verificações de choque de horário na Sala e no Formador (ignorando a própria turma).
     *
     * @param  \Illuminate\Http\Request  $request Dados recebidos do formulário
     * @param  int  $id Identificador primário da turma
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        // 1. Validação básica dos campos
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'string',
            'shift'        => 'required|string|max:255',
            'teacher_id'   => 'required|exists:teachers,id',
            'course_id'    => 'required|exists:courses,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'max_capacity' => 'nullable|integer|min:1',
        ], [
            'name.required'         => 'O nome da turma é de preenchimento obrigatório.',
            'start_time.required'   => 'A hora de início das aulas é obrigatória.',
            'start_time.date_format'=> 'A hora de início deve estar no formato HH:MM.',
            'end_time.required'     => 'A hora de término das aulas é obrigatória.',
            'end_time.date_format'  => 'A hora de término deve estar no formato HH:MM.',
            'days_of_week.required' => 'Selecione pelo menos um dia da semana para as aulas.',
            'shift.required'        => 'O turno é de seleção obrigatória.',
            'teacher_id.required'   => 'Por favor, selecione o formador responsável.',
            'teacher_id.exists'     => 'O formador selecionado é inválido.',
            'course_id.required'    => 'Por favor, selecione o curso associado.',
            'course_id.exists'      => 'O curso selecionado é inválido.',
            'classroom_id.required' => 'Por favor, selecione a sala de aula física.',
            'classroom_id.exists'   => 'A sala de aula selecionada é inválida.',
        ]);

        // 2. REGRA DE NEGÓCIO 1: Validação Temporal básica (end_time > start_time)
        if (strtotime($request->end_time) <= strtotime($request->start_time)) {
            return back()
                ->withInput()
                ->withErrors(['end_time' => 'A hora de término deve ser posterior à hora de início.']);
        }

        // 3. REGRA DE NEGÓCIO 2: Verificar Conflito de Horário na Sala e no Formador ignorando esta turma ($id)
        $conflictError = $this->checkScheduleConflicts($request, $id);
        if ($conflictError) {
            return back()->withInput()->withErrors($conflictError);
        }

        // 4. Atribuição Automática da Capacidade Herdada da Sala
        $classroom = Classroom::findOrFail($request->classroom_id);
        $validatedData['max_capacity'] = $classroom->capacity;

        // Atualiza a turma na base de dados
        $room->update($validatedData);

        // Redireciona com mensagem de sucesso
        return redirect()
            ->route('room.index')
            ->with('success', 'Turma atualizada com sucesso!');
    }

    /**
     * Remove uma turma da base de dados (Soft Delete).
     *
     * @param  int  $id Identificador primário da turma
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()
            ->route('room.index')
            ->with('success', 'Turma eliminada com sucesso!');
    }

    /**
     * Método auxiliar privado para verificar conflitos de horário na Sala Física e no Formador.
     *
     * @param  \Illuminate\Http\Request  $request Dados da requisição
     * @param  int|null  $ignoreRoomId ID da turma a ignorar na verificação (durante edição)
     * @return array|null Array com mensagem de erro associada ao campo com conflito, ou null se não houver choque.
     */
    private function checkScheduleConflicts(Request $request, $ignoreRoomId = null)
    {
        $newStartTime = $request->start_time;
        $newEndTime   = $request->end_time;
        $newDays      = (array) $request->days_of_week;

        // -------------------------------------------------------------------------------------
        // CONFLITO A: VERIFICAR OCUPAÇÃO DA SALA FÍSICA NO MESMO HORÁRIO E DIAS DA SEMANA
        // -------------------------------------------------------------------------------------
        $existingRoomsInClassroom = Room::where('classroom_id', $request->classroom_id)
            ->when($ignoreRoomId, fn ($q) => $q->where('id', '!=', $ignoreRoomId))
            ->where(function ($query) use ($newStartTime, $newEndTime) {
                // Condição de sobreposição temporal: inicio < fim_existente AND fim > inicio_existente
                $query->where('start_time', '<', $newEndTime)
                      ->where('end_time', '>', $newStartTime);
            })
            ->get();

        foreach ($existingRoomsInClassroom as $existingRoom) {
            $existingDays = (array) $existingRoom->days_of_week;
            $overlappingDays = array_intersect($newDays, $existingDays);

            if (!empty($overlappingDays)) {
                $daysList = implode(', ', $overlappingDays);
                $existingStart = date('H:i', strtotime($existingRoom->start_time));
                $existingEnd   = date('H:i', strtotime($existingRoom->end_time));

                return [
                    'classroom_id' => "Conflito de Sala: A Sala {$existingRoom->classroom->number_of_classroom} já está ocupada pela turma '{$existingRoom->name}' em ({$daysList}) no horário das {$existingStart} às {$existingEnd}."
                ];
            }
        }

        // -------------------------------------------------------------------------------------
        // CONFLITO B: VERIFICAR OCUPAÇÃO DO FORMADOR NO MESMO HORÁRIO E DIAS DA SEMANA
        // -------------------------------------------------------------------------------------
        $existingRoomsForTeacher = Room::where('teacher_id', $request->teacher_id)
            ->when($ignoreRoomId, fn ($q) => $q->where('id', '!=', $ignoreRoomId))
            ->where(function ($query) use ($newStartTime, $newEndTime) {
                $query->where('start_time', '<', $newEndTime)
                      ->where('end_time', '>', $newStartTime);
            })
            ->get();

        foreach ($existingRoomsForTeacher as $existingRoom) {
            $existingDays = (array) $existingRoom->days_of_week;
            $overlappingDays = array_intersect($newDays, $existingDays);

            if (!empty($overlappingDays)) {
                $daysList = implode(', ', $overlappingDays);
                $existingStart = date('H:i', strtotime($existingRoom->start_time));
                $existingEnd   = date('H:i', strtotime($existingRoom->end_time));

                return [
                    'teacher_id' => "Conflito de Formador: O formador {$existingRoom->teacher->name} já leciona a turma '{$existingRoom->name}' em ({$daysList}) no horário das {$existingStart} às {$existingEnd}."
                ];
            }
        }

        // Sem qualquer conflito detetado
        return null;
    }
}
