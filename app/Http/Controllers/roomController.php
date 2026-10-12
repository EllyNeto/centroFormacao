<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Classroom;
use Illuminate\Http\Request;

/**
 * Controlador roomController - Gestão Integral de Turmas (Rooms).
 * 
 * Módulo 4: Turmas
 * 
 * Responsabilidades:
 * - Listagem de turmas com cálculo de vagas ocupadas e disponíveis.
 * - Criação e atualização de turmas com lotação máxima herdada da sala física.
 * - Verificação rigorosa contra choques de horários:
 *   1. Na Sala Física (não permite duas turmas na mesma sala no mesmo horário).
 *   2. No Formador (não permite o mesmo formador em duas turmas no mesmo horário).
 *   3. No Mesmo Curso (impede sobreposição de turmas do mesmo curso nos mesmos dias e horário).
 * - Proteção de integridade no Soft Delete contra eliminação de turmas com inscrições ou alunos.
 */
class roomController extends Controller
{
    /**
     * Exibe a listagem de todas as turmas registadas no centro de formação.
     * Carrega as relações de Curso, Formador, Sala Física e Inscrições.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todas as turmas ordenadas pela criação mais recente,
        // carregando antecipadamente as entidades associadas (Eager Loading).
        $rooms = Room::with(['teacher', 'course', 'classroom', 'enrollments', 'students'])
            ->latest()
            ->get();

        // Retorna a vista da listagem de turmas
        return view('admin.room.list.index', compact('rooms'));
    }

    /**
     * Exibe o formulário para registo de uma nova turma.
     * Carrega todos os cursos ativos, formadores e salas físicas disponíveis.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Obtém apenas cursos ativos e ordenados por nome
        $courses = Course::where('status', true)->orderBy('name', 'asc')->get();

        // Obtém formadores disponíveis ordenados por nome
        $teachers = Teacher::orderBy('name', 'asc')->get();

        // Obtém todas as salas físicas cadastradas
        $classrooms = Classroom::orderBy('number_of_classroom', 'asc')->get();

        // Retorna o formulário de criação de turma
        return view('admin.room.create.index', compact('teachers', 'courses', 'classrooms'));
    }

    /**
     * Valida os dados submetidos e armazena uma nova turma na base de dados.
     * Aplica herança de capacidade da sala e verificações de conflito temporal.
     *
     * @param \Illuminate\Http\Request $request Dados da requisição HTTP
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validação em linha (inline validation) com mensagens personalizadas em português
        $validatedData = $request->validate([
            'name'           => 'required|string|max:255',
            'course_id'      => 'required|exists:courses,id',
            'teacher_id'     => 'required|exists:teachers,id',
            'classroom_id'   => 'required|exists:classrooms,id',
            'shift'          => 'required|string|in:Manhã,Tarde,Pós-Laboral',
            'days_of_week'   => 'required|array|min:1',
            'days_of_week.*' => 'string|in:Segunda-feira,Terça-feira,Quarta-feira,Quinta-feira,Sexta-feira,Sábado',
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
        ], [
            'name.required'                 => 'O nome descritivo da turma é obrigatório.',
            'course_id.required'            => 'Por favor, selecione o curso associado.',
            'course_id.exists'              => 'O curso selecionado não é válido.',
            'teacher_id.required'           => 'Por favor, selecione o formador responsável.',
            'teacher_id.exists'             => 'O formador selecionado não é válido.',
            'classroom_id.required'         => 'A seleção da sala de aula física é obrigatória.',
            'classroom_id.exists'           => 'A sala de aula física selecionada é inválida.',
            'shift.required'                => 'O turno de formação é obrigatório.',
            'shift.in'                      => 'O turno deve ser Manhã, Tarde ou Pós-Laboral.',
            'days_of_week.required'         => 'Selecione pelo menos um dia da semana para as aulas.',
            'days_of_week.min'              => 'Selecione pelo menos um dia da semana para as aulas.',
            'start_time.required'           => 'A hora de início das aulas é obrigatória.',
            'start_time.date_format'        => 'A hora de início deve estar no formato HH:MM.',
            'end_time.required'             => 'A hora de término das aulas é obrigatória.',
            'end_time.date_format'          => 'A hora de término deve estar no formato HH:MM.',
            'end_date.after_or_equal'       => 'A data de fim deve ser igual ou posterior à data de início.',
        ]);

        // 2. Validação Temporal: Hora de término deve ser estritamente posterior à hora de início
        if (strtotime($request->end_time) <= strtotime($request->start_time)) {
            return back()
                ->withInput()
                ->withErrors(['end_time' => 'A hora de término deve ser posterior à hora de início.']);
        }

        // 3. Verificação de Conflitos de Horários (Sala, Formador e Mesmo Curso)
        $conflictError = $this->checkScheduleConflicts($request);
        if ($conflictError) {
            return back()->withInput()->withErrors($conflictError);
        }

        // 4. Regra de Negócio: Capacidade máxima da turma é sempre herdada da Sala Física
        $classroom = Classroom::findOrFail($request->classroom_id);
        $validatedData['max_capacity'] = $classroom->capacity;

        // 5. Regra de Negócio: O estado inicial de uma nova turma é sempre 'Aberta' (aceita inscrições)
        $validatedData['status'] = 'Aberta';

        // Cria a turma na base de dados
        Room::create($validatedData);

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()->route('room.index')->with('success', 'Turma criada com sucesso!');
    }

    /**
     * Exibe o perfil detalhado de uma turma específica.
     * Apresenta os horários, formador, curso, sala e lista de alunos matriculados e inscrições.
     *
     * @param int $id Identificador primário da turma
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura a turma carregando os relacionamentos necessários
        $room = Room::with([
            'teacher',
            'course',
            'classroom',
            'enrollments',
            'students'
        ])->findOrFail($id);

        // Retorna a vista de detalhes da turma
        return view('admin.room.details.index', compact('room'));
    }

    /**
     * Exibe a vista de impressão com a lista nominal oficial dos formandos desta turma.
     *
     * @param int $id Identificador primário da turma
     * @return \Illuminate\View\View
     */
    public function printStudents($id)
    {
        // Procura a turma carregando os formandos matriculados, curso, formador e sala
        $room = Room::with([
            'teacher',
            'course',
            'classroom',
            'students',
            'enrollments'
        ])->findOrFail($id);

        // Retorna a vista limpa e formatada para impressão direta
        return view('admin.room.details.listOfStudents', compact('room'));
    }

    /**
     * Exibe o formulário de edição de dados de uma turma existente.
     *
     * @param int $id Identificador primário da turma
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Procura a turma existente
        $room = Room::findOrFail($id);

        // Carrega as opções de seleção
        $courses    = Course::orderBy('name', 'asc')->get();
        $teachers   = Teacher::orderBy('name', 'asc')->get();
        $classrooms = Classroom::orderBy('number_of_classroom', 'asc')->get();

        // Retorna a vista de edição
        return view('admin.room.edit.index', compact('room', 'teachers', 'courses', 'classrooms'));
    }

    /**
     * Valida e atualiza os dados da turma na base de dados.
     * Reavalia conflitos de horários ignorando a própria turma sendo editada.
     *
     * @param \Illuminate\Http\Request $request Dados da requisição
     * @param int $id Identificador primário da turma
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Procura a turma a ser atualizada
        $room = Room::findOrFail($id);

        // 1. Validação dos campos
        $validatedData = $request->validate([
            'name'           => 'required|string|max:255',
            'course_id'      => 'required|exists:courses,id',
            'teacher_id'     => 'required|exists:teachers,id',
            'classroom_id'   => 'required|exists:classrooms,id',
            'shift'          => 'required|string|in:Manhã,Tarde,Pós-Laboral',
            'days_of_week'   => 'required|array|min:1',
            'days_of_week.*' => 'string|in:Segunda-feira,Terça-feira,Quarta-feira,Quinta-feira,Sexta-feira,Sábado',
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'status'         => 'required|string|in:Aberta,Em curso,Concluída',
        ], [
            'name.required'                 => 'O nome descritivo da turma é obrigatório.',
            'course_id.required'            => 'Por favor, selecione o curso associado.',
            'course_id.exists'              => 'O curso selecionado não é válido.',
            'teacher_id.required'           => 'Por favor, selecione o formador responsável.',
            'teacher_id.exists'             => 'O formador selecionado não é válido.',
            'classroom_id.required'         => 'A seleção da sala de aula física é obrigatória.',
            'classroom_id.exists'           => 'A sala de aula física selecionada é inválida.',
            'shift.required'                => 'O turno de formação é obrigatório.',
            'shift.in'                      => 'O turno deve ser Manhã, Tarde ou Pós-Laboral.',
            'days_of_week.required'         => 'Selecione pelo menos um dia da semana para as aulas.',
            'days_of_week.min'              => 'Selecione pelo menos um dia da semana para as aulas.',
            'start_time.required'           => 'A hora de início das aulas é obrigatória.',
            'start_time.date_format'        => 'A hora de início deve estar no formato HH:MM.',
            'end_time.required'             => 'A hora de término das aulas é obrigatória.',
            'end_time.date_format'          => 'A hora de término deve estar no formato HH:MM.',
            'end_date.after_or_equal'       => 'A data de fim deve ser igual ou posterior à data de início.',
            'status.required'               => 'O estado da turma é obrigatório.',
            'status.in'                     => 'O estado da turma selecionado é inválido.',
        ]);

        // 2. Validação Temporal: Hora de término > início
        if (strtotime($request->end_time) <= strtotime($request->start_time)) {
            return back()
                ->withInput()
                ->withErrors(['end_time' => 'A hora de término deve ser posterior à hora de início.']);
        }

        // 3. Verificação de Conflitos de Horários ignorando esta turma ($id)
        $conflictError = $this->checkScheduleConflicts($request, $id);
        if ($conflictError) {
            return back()->withInput()->withErrors($conflictError);
        }

        // 4. Regra de Negócio: Ajusta a capacidade máxima para a capacidade da sala selecionada
        $classroom = Classroom::findOrFail($request->classroom_id);
        $validatedData['max_capacity'] = $classroom->capacity;

        // Atualiza a turma na base de dados
        $room->update($validatedData);

        // Redireciona com mensagem de sucesso
        return redirect()->route('room.index')->with('success', 'Turma atualizada com sucesso!');
    }

    /**
     * Remove uma turma da base de dados (Soft Delete).
     * Impede a remoção se houver formandos matriculados ou candidaturas ativas.
     *
     * @param int $id Identificador primário da turma
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Procura a turma a ser eliminada
        $room = Room::findOrFail($id);

        // Regra de Negócio: Proteger histórico e integridade de formandos já matriculados
        if ($room->hasActiveStudents()) {
            return redirect()->route('room.index')->with(
                'error',
                'Impossível eliminar a turma: existem formandos matriculados a frequentar esta turma.'
            );
        }

        // Regra de Negócio: Proteger candidaturas ativas vinculadas a esta turma
        if ($room->hasActiveEnrollments()) {
            return redirect()->route('room.index')->with(
                'error',
                'Impossível eliminar a turma: existem inscrições ativas associadas a esta turma.'
            );
        }

        // Executa a eliminação lógica (Soft Delete)
        $room->delete();

        // Redireciona com mensagem de sucesso
        return redirect()->route('room.index')->with('success', 'Turma eliminada com sucesso!');
    }

    /**
     * Método auxiliar privado para verificar conflitos de horário em três níveis:
     * 1. Sala de Aula Física ocupada no mesmo dia e horário.
     * 2. Formador atribuído a outra turma no mesmo dia e horário.
     * 3. Turma concorrente do mesmo curso a decorrer no mesmo dia e horário.
     *
     * @param \Illuminate\Http\Request $request Dados da requisição
     * @param int|null $ignoreRoomId ID da turma a ignorar na verificação (durante atualização)
     * @return array|null Array com o erro e o campo associado, ou null se não houver conflito.
     */
    private function checkScheduleConflicts(Request $request, $ignoreRoomId = null)
    {
        // Normaliza horários para o formato HH:MM:SS para comparação precisa no banco de dados
        $newStartTime = date('H:i:s', strtotime($request->start_time));
        $newEndTime   = date('H:i:s', strtotime($request->end_time));
        $newDays      = array_map('trim', (array) $request->days_of_week);

        // -------------------------------------------------------------------------------------
        // CONFLITO 1: VERIFICAR SE A SALA FÍSICA JÁ ESTÁ OCUPADA NO MESMO HORÁRIO E DIAS
        // -------------------------------------------------------------------------------------
        $existingRoomsInClassroom = Room::where('classroom_id', $request->classroom_id)
            ->whereIn('status', ['Aberta', 'Em curso'])
            ->when($ignoreRoomId, fn ($q) => $q->where('id', '!=', $ignoreRoomId))
            ->where(function ($query) use ($newStartTime, $newEndTime) {
                // Condição de sobreposição temporal: inicio < fim_novo E fim > inicio_novo
                $query->where('start_time', '<', $newEndTime)
                      ->where('end_time', '>', $newStartTime);
            })
            ->get();

        foreach ($existingRoomsInClassroom as $existingRoom) {
            $existingDays = array_map('trim', (array) $existingRoom->days_of_week);
            $overlappingDays = array_intersect($newDays, $existingDays);

            if (!empty($overlappingDays)) {
                $daysList      = implode(', ', $overlappingDays);
                $existingStart = date('H:i', strtotime($existingRoom->start_time));
                $existingEnd   = date('H:i', strtotime($existingRoom->end_time));

                return [
                    'classroom_id' => "Conflito de Sala: A Sala {$existingRoom->classroom->number_of_classroom} já está ocupada pela turma '{$existingRoom->name}' em ({$daysList}) das {$existingStart} às {$existingEnd}."
                ];
            }
        }

        // -------------------------------------------------------------------------------------
        // CONFLITO 2: VERIFICAR SE O FORMADOR JÁ TEM AULA NO MESMO HORÁRIO E DIAS
        // -------------------------------------------------------------------------------------
        $existingRoomsForTeacher = Room::where('teacher_id', $request->teacher_id)
            ->whereIn('status', ['Aberta', 'Em curso'])
            ->when($ignoreRoomId, fn ($q) => $q->where('id', '!=', $ignoreRoomId))
            ->where(function ($query) use ($newStartTime, $newEndTime) {
                $query->where('start_time', '<', $newEndTime)
                      ->where('end_time', '>', $newStartTime);
            })
            ->get();

        foreach ($existingRoomsForTeacher as $existingRoom) {
            $existingDays = array_map('trim', (array) $existingRoom->days_of_week);
            $overlappingDays = array_intersect($newDays, $existingDays);

            if (!empty($overlappingDays)) {
                $daysList      = implode(', ', $overlappingDays);
                $existingStart = date('H:i', strtotime($existingRoom->start_time));
                $existingEnd   = date('H:i', strtotime($existingRoom->end_time));

                return [
                    'teacher_id' => "Conflito de Formador: O formador {$existingRoom->teacher->name} já leciona a turma '{$existingRoom->name}' em ({$daysList}) das {$existingStart} às {$existingEnd}."
                ];
            }
        }

        // -------------------------------------------------------------------------------------
        // CONFLITO 3: VERIFICAR SE O MESMO CURSO JÁ TEM OUTRA TURMA NO MESMO HORÁRIO E DIAS
        // -------------------------------------------------------------------------------------
        $existingRoomsSameCourse = Room::where('course_id', $request->course_id)
            ->whereIn('status', ['Aberta', 'Em curso'])
            ->when($ignoreRoomId, fn ($q) => $q->where('id', '!=', $ignoreRoomId))
            ->where(function ($query) use ($newStartTime, $newEndTime) {
                $query->where('start_time', '<', $newEndTime)
                      ->where('end_time', '>', $newStartTime);
            })
            ->get();

        foreach ($existingRoomsSameCourse as $existingRoom) {
            $existingDays = array_map('trim', (array) $existingRoom->days_of_week);
            $overlappingDays = array_intersect($newDays, $existingDays);

            if (!empty($overlappingDays)) {
                $daysList      = implode(', ', $overlappingDays);
                $existingStart = date('H:i', strtotime($existingRoom->start_time));
                $existingEnd   = date('H:i', strtotime($existingRoom->end_time));
                $courseName    = $existingRoom->course->name ?? 'selecionado';

                return [
                    'course_id' => "Conflito de Curso: O curso '{$courseName}' já possui a turma ativa '{$existingRoom->name}' agendada em ({$daysList}) das {$existingStart} às {$existingEnd}. Para evitar duplicidade de horários, selecione outro turno ou dias."
                ];
            }
        }

        // Sem qualquer conflito detetado
        return null;
    }
}

