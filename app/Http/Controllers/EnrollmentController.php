<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador responsável pela gestão de Inscrições / Candidaturas (Enrollment).
 *
 * Módulo 5: Inscrições
 * Implementa o fluxo completo de pré-inscrições, validação em linha,
 * upload de documentos e gestão de vagas/lista de espera por turma.
 *
 * Regras de Negócio Implementadas:
 * R1  - Uma pessoa (mesmo BI) só pode ter uma inscrição ativa por curso.
 * R11 - O curso selecionado deve ter pelo menos uma turma 'Aberta'.
 * R9  - Mudança de curso: só antes de Matriculado; recalcula saldo.
 * R4  - Identificação: código de aluno (opcional) ou BI.
 */
class EnrollmentController extends Controller
{
    /**
     * Exibe a listagem completa de candidaturas e inscrições.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $enrollments = Enrollment::with(['course', 'room'])
            ->latest()
            ->get();

        return view('admin.enrollment.list.index', compact('enrollments'));
    }

    /**
     * Exibe o formulário de registo de nova candidatura.
     * Carrega cursos com indicação de disponibilidade de turmas abertas.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Carrega todos os cursos com contagem de turmas abertas
        $courses = Course::withCount([
            'rooms as open_rooms_count' => fn($q) => $q->where('status', 'Aberta'),
        ])->orderBy('name', 'asc')->get();

        return view('admin.enrollment.create.index', compact('courses'));
    }

    /**
     * Valida e armazena uma nova candidatura na base de dados.
     * O estado inicial é sempre 'Pendente' e sem turma associada até haver pagamento.
     *
     * @param \App\Http\Requests\EnrollmentRequest $request Dados da requisição HTTP
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EnrollmentRequest $request)
    {
        // 1. Validação em linha dos dados pessoais, curso e turno pretendido
        $validatedData = $request->validated();

        // 2. R4 — Validação cruzada: código de aluno + BI devem coincidir
        if (!empty($validatedData['student_code'])) {
            $student = Student::where('code', $validatedData['student_code'])->first();
            if ($student && $student->enrollment) {
                $storedBi = $student->enrollment->number_of_identify;
                if ($storedBi !== $validatedData['number_of_identify']) {
                    return back()
                        ->withInput()
                        ->withErrors(['student_code' => 'O código de aluno não corresponde ao BI indicado.']);
                }
            }
        }

        // 3. R11 — Verificar disponibilidade de turma 'Aberta' com vagas para o curso
        $openRooms = Room::where('course_id', $validatedData['course_id'])
            ->where('status', 'Aberta')
            ->orderByRaw('CASE WHEN shift = ? THEN 0 ELSE 1 END', [$validatedData['shift']])
            ->orderBy('id', 'asc')
            ->get();

        $availableRoom = $openRooms->first(fn(Room $room) => !$room->isFull());
        $joinWaitlist  = $request->boolean('join_waitlist');

        if (!$availableRoom && !$joinWaitlist) {
            return back()
                ->withInput()
                ->withErrors([
                    'course_id' => 'O curso selecionado não possui turmas abertas com vagas disponíveis neste momento. Marque a opção "Ir para a Lista de Espera" abaixo para registar o aluno e adicioná-lo automaticamente assim que uma turma for criada.',
                ]);
        }

        // 4. R1 — Verificar inscrição ativa no mesmo curso pelo mesmo BI
        $existingEnrollment = Enrollment::activeForSameCourse(
            $validatedData['number_of_identify'],
            $validatedData['course_id']
        )->first();

        if ($existingEnrollment) {
            return back()
                ->withInput()
                ->withErrors(['course_id' => 'Este candidato já possui a inscrição ' . $existingEnrollment->formattedCode() . ' neste curso. Para repetir o curso, registe um novo pagamento nessa inscrição após a conclusão da turma.']);
        }

        // 5. Processamento e Armazenamento da Imagem de Identificação
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $uploadedImage = $request->file('image');
            $extension     = $uploadedImage->extension();
            $fileName      = md5($uploadedImage->getClientOriginalName() . time()) . '.' . $extension;
            $validatedData['image'] = $uploadedImage->storeAs('candidates', $fileName, 'public');
        } else {
            $validatedData['image'] = null;
        }

        // 6. Regra de Negócio: Atribui turma aberta se disponível, ou coloca na Lista de Espera
        if ($availableRoom && !$joinWaitlist) {
            $validatedData['room_id'] = $availableRoom->id;
            $validatedData['status']  = 'Pendente';
        } else {
            $validatedData['room_id'] = null;
            $validatedData['status']  = 'Lista de Espera';
        }
        $validatedData['cycle'] = 1;
        $validatedData['date']  = now();

        // Remove campos auxiliares não existentes na tabela
        unset($validatedData['student_code'], $validatedData['join_waitlist']);

        // 7. Criação do registo da candidatura na base de dados
        $enrollment = Enrollment::create($validatedData);

        // 8. Envio de notificação nativa por email para o endereço do candidato
        try {
            if (!empty($enrollment->email)) {
                $enrollment->notify(new \App\Notifications\EnrollmentConfirmationNotification($enrollment));
            }
        } catch (\Throwable $e) {
            // Em caso de indisponibilidade momentânea do serviço de email, regista nos logs sem interromper o fluxo
            \Illuminate\Support\Facades\Log::warning("Falha ao enviar email de candidatura: " . $e->getMessage());
        }

        if ($enrollment->isWaitlist()) {
            return redirect()
                ->route('enrollment.index')
                ->with('success', 'Candidatura registada na Lista de Espera (' . $enrollment->formattedCode() . '). Logo que for criada uma turma para este curso, o aluno será adicionado automaticamente na mesma.');
        }

        return redirect()
            ->route('enrollment.index')
            ->with('success', 'Candidatura registada com sucesso (' . $enrollment->formattedCode() . ') e associada à turma "' . $availableRoom->name . '".');
    }

    /**
     * Exibe o perfil detalhado da candidatura, incluindo dados da turma e pagamentos.
     *
     * @param int $id Identificador primário da inscrição
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $enrollment = Enrollment::with([
            'course',
            'room.classroom',
            'room.teacher',
            'payments.previousRoom',
            'student',
        ])->findOrFail($id);

        // Carrega turmas com estado 'Aberta' do mesmo curso para disponibilizar no modal de efetivação de matrícula
        $openRooms = Room::where('course_id', $enrollment->course_id)
            ->where('status', 'Aberta')
            ->with(['classroom', 'teacher'])
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.enrollment.details.index', compact('enrollment', 'openRooms'));
    }

    /**
     * Exibe o formulário de edição de uma candidatura existente.
     * Permite alterar dados pessoais, curso e turno pretendido.
     * O estado e a turma são geridos pelo fluxo de pagamentos e matrículas.
     *
     * @param int $id Identificador primário da inscrição
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $enrollment = Enrollment::findOrFail($id);

        // Carrega cursos com contagem de turmas abertas
        $courses = Course::withCount([
            'rooms as open_rooms_count' => fn($q) => $q->where('status', 'Aberta'),
        ])->orderBy('name', 'asc')->get();

        return view('admin.enrollment.edit.index', compact('enrollment', 'courses'));
    }

    /**
     * Atualiza os dados de uma candidatura existente na base de dados.
     *
     * @param \App\Http\Requests\EnrollmentRequest $request Dados da requisição HTTP
     * @param int $id Identificador primário da inscrição
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EnrollmentRequest $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);

        // Matriculado não pode mudar de curso
        if ($enrollment->isEnrolled()) {
            return back()
                ->withInput()
                ->withErrors(['course_id' => 'Não é possível alterar o curso de uma inscrição já matriculada.']);
        }

        // 1. Validação em linha dos campos editáveis da candidatura
        $validatedData = $request->validated();

        // 2. R4 — Validação cruzada: código de aluno + BI devem coincidir
        if (!empty($validatedData['student_code'])) {
            $student = Student::where('code', $validatedData['student_code'])->first();
            if ($student && $student->enrollment) {
                $storedBi = $student->enrollment->number_of_identify;
                if ($storedBi !== $validatedData['number_of_identify']) {
                    return back()
                        ->withInput()
                        ->withErrors(['student_code' => 'O código de aluno não corresponde ao BI indicado.']);
                }
            }
        }

        $courseChanged = (int) $validatedData['course_id'] !== (int) $enrollment->course_id;
        $joinWaitlist  = $request->boolean('join_waitlist');

        // 3. R11 — Verificar disponibilidade de turma 'Aberta' caso mude de curso ou esteja sem turma
        $openRooms = Room::where('course_id', $validatedData['course_id'])
            ->where('status', 'Aberta')
            ->orderByRaw('CASE WHEN shift = ? THEN 0 ELSE 1 END', [$validatedData['shift']])
            ->orderBy('id', 'asc')
            ->get();

        $availableRoom = $openRooms->first(fn(Room $room) => !$room->isFull());

        if ($courseChanged && !$availableRoom && !$joinWaitlist) {
            return back()
                ->withInput()
                ->withErrors([
                    'course_id' => 'O curso selecionado não possui turmas abertas com vagas disponíveis neste momento. Marque a opção "Ir para a Lista de Espera" abaixo para prosseguir.',
                ]);
        }

        // 4. R1 — Verificar inscrição ativa no mesmo curso pelo mesmo BI (ignorando esta)
        $existingEnrollment = Enrollment::activeForSameCourse(
            $validatedData['number_of_identify'],
            $validatedData['course_id'],
            $enrollment->id
        )->first();

        if ($existingEnrollment) {
            return back()
                ->withInput()
                ->withErrors(['course_id' => 'Este candidato já possui a inscrição ' . $existingEnrollment->formattedCode() . ' neste curso.']);
        }

        // 5. Substituição da Imagem (caso um novo ficheiro tenha sido enviado)
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($enrollment->image && Storage::disk('public')->exists($enrollment->image)) {
                Storage::disk('public')->delete($enrollment->image);
            }

            $uploadedImage = $request->file('image');
            $extension     = $uploadedImage->extension();
            $fileName      = md5($uploadedImage->getClientOriginalName() . time()) . '.' . $extension;
            $validatedData['image'] = $uploadedImage->storeAs('candidates', $fileName, 'public');
        }

        // Atualiza alocação de turma se mudou de curso ou se solicitou Lista de Espera
        if ($courseChanged || !$enrollment->room_id || $joinWaitlist) {
            if ($availableRoom && !$joinWaitlist) {
                $validatedData['room_id'] = $availableRoom->id;
                if ($enrollment->isWaitlist()) {
                    $validatedData['status'] = 'Pendente';
                }
            } elseif (!$availableRoom || $joinWaitlist) {
                $validatedData['room_id'] = null;
                $validatedData['status']  = 'Lista de Espera';
            }
        }

        // Remove campos auxiliares não existentes na tabela
        unset($validatedData['student_code'], $validatedData['join_waitlist']);

        // 6. Atualização dos dados no modelo
        $enrollment->update($validatedData);

        // 7. R9 — Se mudou de curso, recalcular o estado da inscrição
        if ($courseChanged) {
            $enrollment->refresh();
            $enrollment->syncPaymentStatus();

            $balance = $enrollment->remainingBalance();
            $credit  = $enrollment->creditBalance();

            if ($credit > 0) {
                $msg = "Candidatura atualizada. O curso anterior era mais caro; existe um crédito de " . number_format($credit, 2, ',', '.') . " Kz.";
            } elseif ($balance > 0) {
                $msg = "Candidatura atualizada. O novo curso tem um valor superior; faltam " . number_format($balance, 2, ',', '.') . " Kz para completar o pagamento.";
            } else {
                $msg = "Candidatura '{$enrollment->name}' atualizada com sucesso!";
            }

            return redirect()->route('enrollment.index')->with('success', $msg);
        }

        return redirect()
            ->route('enrollment.index')
            ->with('success', "Candidatura '{$enrollment->name}' atualizada com sucesso!");
    }

    /**
     * Remove uma candidatura do sistema via Soft Delete.
     * Impede remoção caso já existam pagamentos confirmados ou aluno oficial matriculado.
     *
     * @param int $id Identificador primário da inscrição
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $enrollment = Enrollment::with(['payments', 'student'])->findOrFail($id);

        // Regra de Integridade: Impedir eliminação se o formando já estiver oficializado
        if ($enrollment->student) {
            return back()->with('error', 'Não é possível eliminar esta candidatura pois o formando já se encontra matriculado oficialmente.');
        }

        // Regra de Integridade: Impedir eliminação se houver pagamentos confirmados associados
        if ($enrollment->payments()->where('status', 'Confirmado')->exists()) {
            return back()->with('error', 'Não é possível eliminar esta candidatura porque possui pagamentos confirmados registados.');
        }

        $enrollment->delete();

        return redirect()
            ->route('enrollment.index')
            ->with('success', 'Candidatura eliminada com sucesso!');
    }
}
