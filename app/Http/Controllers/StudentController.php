<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Controlador StudentController - Gestão Integral de Formandos / Alunos Oficiais.
 *
 * Módulo 7: Formandos
 * 
 * Responsabilidades:
 * - Listagem de todos os formandos matriculados com as respetivas turmas e cursos.
 * - Efetivação de candidaturas com pagamento liquidado ('Pago') em formandos oficiais ('Matriculado').
 * - Atribuição e troca de turma com controlo rigoroso da capacidade e lotação da sala física.
 * - Ficha cadastral detalhada do formando (dados civis, académicos e financeiros).
 * - Edição de dados pessoais e gestão de fotografias no armazenamento público.
 * - Eliminação lógica (Soft Delete) com reversão de estado da inscrição de origem.
 *
 * Regras de Negócio Aplicadas:
 * R9  - Só candidaturas com propina liquidada ('Pago') podem ser efetivadas como Formando Oficial.
 * R10 - Ao efetivar um formando, a inscrição de origem passa ao estado 'Matriculado' e vincula-se à turma.
 * R11 - A turma escolhida deve pertencer ao curso da inscrição, estar com estado 'Aberta' e ter vagas disponíveis.
 * R12 - Ao remover um formando, a inscrição de origem retorna ao estado 'Pago' para permitir nova alocação.
 */
class StudentController extends Controller
{
    /**
     * Apresenta a listagem geral de todos os formandos oficiais matriculados.
     * Carrega antecipadamente as relações com a Turma, Sala, Formador, Curso e Inscrição.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Consulta todos os formandos ativos com as suas relações associadas
        $students = Student::with([
            'room.course',
            'room.classroom',
            'room.teacher',
            'enrollment'
        ])
        ->latest()
        ->get();

        // Contadores estatísticos para o cabeçalho da listagem
        $totalStudents = $students->count();
        $totalRooms    = Room::count();

        // Renderiza a vista de listagem com os dados
        return view('admin.student.list.index', compact('students', 'totalStudents', 'totalRooms'));
    }

    /**
     * Apresenta o formulário para registo manual de uma nova matrícula de formando.
     * Carrega as inscrições que já têm o pagamento liquidado ('Pago') e ainda não foram matriculadas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Obtém candidaturas no estado 'Pago' que ainda não possuem registo de formando
        $eligibleEnrollments = Enrollment::where('status', 'Pago')
            ->whereDoesntHave('student')
            ->with(['course', 'room'])
            ->orderBy('name', 'asc')
            ->get();

        // Permite pré-selecionar uma inscrição específica caso o ID seja passado via URL (?enrollment_id=X)
        $selectedEnrollmentId = $request->query('enrollment_id');
        $selectedEnrollment   = null;

        if ($selectedEnrollmentId) {
            $selectedEnrollment = $eligibleEnrollments->firstWhere('id', (int) $selectedEnrollmentId);
        }

        // Obtém todas as turmas abertas para seleção de alocação
        $openRooms = Room::where('status', 'Aberta')
            ->with(['course', 'classroom', 'teacher'])
            ->orderBy('name', 'asc')
            ->get();

        // Retorna a vista do formulário de criação
        return view('admin.student.create.index', compact('eligibleEnrollments', 'selectedEnrollment', 'openRooms'));
    }

    /**
     * Valida os dados submetidos e efetiva a matrícula de um formando no sistema.
     * Vincula a inscrição à turma selecionada e atualiza o estado para 'Matriculado'.
     *
     * @param  \App\Http\Requests\StudentRequest  $request Dados recebidos da requisição
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StudentRequest $request)
    {
        // 1. Validação dos campos obrigatórios da matrícula
        $validatedData = $request->validated();

        // 2. Regra de Negócio R9: Verificar se a inscrição está apta para efetivação (estado 'Pago')
        $enrollment = Enrollment::findOrFail($validatedData['enrollment_id']);

        if (!$enrollment->isPaid()) {
            return back()
                ->withInput()
                ->withErrors(['enrollment_id' => 'Apenas candidaturas com propina totalmente liquidada (estado Pago) podem ser efetivadas.']);
        }

        // 3. Regra de Negócio R11: Verificar turma (pertence ao mesmo curso, status Aberta e vagas disponíveis)
        $room = Room::findOrFail($validatedData['room_id']);

        if ((int) $room->course_id !== (int) $enrollment->course_id) {
            return back()
                ->withInput()
                ->withErrors(['room_id' => 'A turma selecionada não corresponde ao curso escolhido na candidatura.']);
        }

        if ($room->status !== 'Aberta') {
            return back()
                ->withInput()
                ->withErrors(['room_id' => 'A turma selecionada não se encontra no estado Aberta para aceitar formandos.']);
        }

        // Verifica lotação máxima da turma
        if ($room->isFull()) {
            return back()
                ->withInput()
                ->withErrors(['room_id' => "A turma '{$room->name}' atingiu a lotação máxima de {$room->max_capacity} lugares."]);
        }

        // 4. Tratamento do upload de fotografia (se enviada nova, ou herda da inscrição)
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $uploadedImage = $request->file('image');
            $extension     = $uploadedImage->extension();
            $fileName      = md5($uploadedImage->getClientOriginalName() . time()) . '.' . $extension;
            $validatedData['image'] = $uploadedImage->storeAs('students', $fileName, 'public');
        } elseif ($enrollment->image) {
            // Reaproveita a fotografia da candidatura se não for enviada uma nova
            $validatedData['image'] = $enrollment->image;
        } else {
            $validatedData['image'] = null;
        }

        // 5. Criação do registo oficial do formando na base de dados
        // Nota: O código único 'CF-ANO-NNNN' é gerado automaticamente pelo boot() do modelo Student
        $student = Student::create($validatedData);

        // 6. Regra de Negócio R10: Atualiza a inscrição de origem para o estado 'Matriculado' e associa a turma
        $enrollment->update([
            'room_id' => $room->id,
            'status'  => 'Matriculado',
        ]);

        // 7. Envio de notificação nativa por email de boas-vindas e confirmação de matrícula ao formando
        try {
            if (!empty($student->email)) {
                $student->notify(new \App\Notifications\StudentWelcomeNotification($student));
            }
        } catch (\Throwable $e) {
            // Registo no log para garantir tolerância a falhas na entrega de emails
            \Illuminate\Support\Facades\Log::warning("Falha ao enviar email de matrícula ao formando: " . $e->getMessage());
        }

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()
            ->route('student.index')
            ->with('success', "Formando '{$student->name}' matriculado com sucesso! Código atribuído: {$student->formattedCode()}.");
    }

    /**
     * Efetivação direta a partir da página de detalhes da inscrição (Módulo 5 → Módulo 7).
     * Rota: POST /inscricao/{id}/efectivar
     *
     * Permite matricular o candidato diretamente quando a candidatura já está no estado 'Pago'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id Identificador primário da inscrição
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFromEnrollment(Request $request, $id)
    {
        // Procura a inscrição com o curso e estudante existente
        $enrollment = Enrollment::with(['student', 'course'])->findOrFail($id);

        // 1. Verifica se já se encontra matriculado
        if ($enrollment->student) {
            return redirect()
                ->route('student.show', $enrollment->student->id)
                ->with('info', 'Esta candidatura já possui um formando matriculado no sistema.');
        }

        // 2. Regra R9: Candidatura tem de estar no estado 'Pago'
        if (!$enrollment->isPaid()) {
            return back()->with('error', 'Apenas inscrições com pagamento confirmado podem ser efetivadas em formando.');
        }

        // 3. Determina a turma a alocar: usa a turma enviada no formulário modal ou a já vinculada à inscrição
        $roomId = $request->input('room_id', $enrollment->room_id);

        if (!$roomId) {
            return back()->with('error', 'Por favor, selecione uma turma ativa para alocar o formando.');
        }

        // 4. Regra R11: Valida a turma escolhida
        $room = Room::findOrFail($roomId);

        if ((int) $room->course_id !== (int) $enrollment->course_id) {
            return back()->with('error', 'A turma selecionada não pertence ao curso desta candidatura.');
        }

        if ($room->status !== 'Aberta') {
            return back()->with('error', 'A turma selecionada não está aberta para receber novos alunos.');
        }

        if ($room->isFull()) {
            return back()->with('error', "A turma '{$room->name}' já se encontra lotada ({$room->max_capacity} vagas).");
        }

        // 5. Criação do registo do Formando Oficial a partir dos dados já validados da inscrição
        $student = Student::create([
            'enrollment_id'      => $enrollment->id,
            'room_id'            => $room->id,
            'name'               => $enrollment->name,
            'email'              => $enrollment->email,
            'number_of_identify' => $enrollment->number_of_identify,
            'phone'              => $enrollment->phone,
            'image'              => $enrollment->image,
        ]);

        // 6. Atualização do estado da inscrição para 'Matriculado'
        $enrollment->update([
            'room_id' => $room->id,
            'status'  => 'Matriculado',
        ]);

        // 7. Envio de notificação nativa por email de boas-vindas e confirmação de matrícula ao formando
        try {
            if (!empty($student->email)) {
                $student->notify(new \App\Notifications\StudentWelcomeNotification($student));
            }
        } catch (\Throwable $e) {
            // Registo no log para garantir tolerância a falhas na entrega de emails
            \Illuminate\Support\Facades\Log::warning("Falha ao enviar email de matrícula ao formando: " . $e->getMessage());
        }

        // Redireciona para os detalhes do formando recém-criado
        return redirect()
            ->route('student.show', $student->id)
            ->with('success', "Matrícula oficializada com sucesso! O formando recebeu o código {$student->formattedCode()}.");
    }

    /**
     * Exibe o perfil cadastral e académico detalhado de um formando específico.
     * Apresenta dados pessoais, turma alocada, formador, sala física e histórico de pagamentos.
     *
     * @param  int  $id Identificador primário do formando
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura o formando com carregamento antecipado de todas as relações relevantes
        $student = Student::with([
            'room.course',
            'room.classroom',
            'room.teacher',
            'enrollment.course',
            'enrollment.payments' => fn($q) => $q->orderBy('id', 'desc'),
        ])->findOrFail($id);

        // Renderiza a vista de detalhes do formando
        return view('admin.student.details.index', compact('student'));
    }

    /**
     * Exibe o formulário de edição dos dados de um formando existente.
     * Permite atualizar contactos, dados civis, fotografia e efetuar transferência de turma.
     *
     * @param  int  $id Identificador primário do formando
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Procura o formando pelo ID
        $student = Student::with(['room.course', 'enrollment.course'])->findOrFail($id);

        // Determina o ID do curso para listar apenas turmas compatíveis
        $courseId = $student->room ? $student->room->course_id : ($student->enrollment ? $student->enrollment->course_id : null);

        // Carrega turmas abertas do mesmo curso para permitir transferência de turma
        $openRooms = Room::when($courseId, fn($q) => $q->where('course_id', $courseId))
            ->where('status', 'Aberta')
            ->with(['classroom', 'teacher'])
            ->orderBy('name', 'asc')
            ->get();

        // Renderiza a vista de edição
        return view('admin.student.edit.index', compact('student', 'openRooms'));
    }

    /**
     * Atualiza as informações cadastrais do formando na base de dados.
     * Caso haja mudança de turma, valida a capacidade disponível na nova turma.
     *
     * @param  \App\Http\Requests\StudentRequest  $request Dados recebidos da requisição HTTP
     * @param  int  $id Identificador primário do formando
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StudentRequest $request, $id)
    {
        // Procura o formando existente
        $student = Student::with(['room', 'enrollment'])->findOrFail($id);

        // 1. Validação em linha dos campos editáveis do formando
        $validatedData = $request->validated();

        // 2. Se a turma foi alterada (Transferência de Turma), verifica a capacidade disponível na nova turma.
        $currentRoomId = (int) $student->room_id;
        $newRoomId     = (int) $validatedData['room_id'];

        if ($currentRoomId !== $newRoomId) {
            $newRoom = Room::findOrFail($newRoomId);

            // Garante que a nova turma é do mesmo curso
            $expectedCourseId = $student->room ? $student->room->course_id : ($student->enrollment ? $student->enrollment->course_id : null);
            if ($expectedCourseId && (int) $newRoom->course_id !== (int) $expectedCourseId) {
                return back()
                    ->withInput()
                    ->withErrors(['room_id' => 'Não é permitido transferir o formando para uma turma de um curso diferente.']);
            }

            // Verifica se a nova turma tem vagas disponíveis
            if ($newRoom->isFull()) {
                return back()
                    ->withInput()
                    ->withErrors(['room_id' => "A nova turma '{$newRoom->name}' já se encontra lotada."]);
            }

            // Atualiza também a turma associada na inscrição de origem
            if ($student->enrollment) {
                $student->enrollment->update(['room_id' => $newRoomId]);
            }
        }

        // 3. Processamento e substituição de fotografia caso enviada uma nova
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Remove a fotografia anterior se existir no disco público
            if ($student->image && Storage::disk('public')->exists($student->image)) {
                Storage::disk('public')->delete($student->image);
            }

            $uploadedImage = $request->file('image');
            $extension     = $uploadedImage->extension();
            $fileName      = md5($uploadedImage->getClientOriginalName() . time()) . '.' . $extension;
            $validatedData['image'] = $uploadedImage->storeAs('students', $fileName, 'public');
        }

        // 4. Atualiza os dados do formando
        $student->update($validatedData);

        // Atualiza dados na inscrição de origem se aplicável para consistência
        if ($student->enrollment) {
            $student->enrollment->update([
                'name'               => $validatedData['name'],
                'email'              => $validatedData['email'],
                'number_of_identify' => $validatedData['number_of_identify'],
                'phone'              => $validatedData['phone'],
            ]);
        }

        // Redireciona para o perfil com mensagem de sucesso
        return redirect()
            ->route('student.show', $student->id)
            ->with('success', "Dados do formando '{$student->name}' atualizados com sucesso!");
    }

    /**
     * Remove o registo de um formando do sistema através de eliminação lógica (Soft Delete).
     * Reverte o estado da inscrição de origem de volta para 'Pago' para permitir eventual reatribuição.
     *
     * @param  int  $id Identificador primário do formando
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Procura o formando com a sua inscrição associada
        $student = Student::with('enrollment')->findOrFail($id);

        // Regra de Negócio R12: Se existir inscrição de origem, reverte o estado para 'Pago'
        if ($student->enrollment) {
            $student->enrollment->update([
                'status' => 'Pago',
            ]);
        }

        // Executa a eliminação lógica do formando
        $student->delete();

        // Redireciona para a listagem com mensagem explicativa de sucesso
        return redirect()
            ->route('student.index')
            ->with('success', "Formando '{$student->name}' removido com sucesso. A inscrição de origem retornou ao estado 'Pago'.");
    }
}
