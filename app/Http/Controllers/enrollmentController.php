<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador responsável pela gestão de Inscrições / Candidaturas (Enrollment).
 *
 * Módulo 5: Inscrições
 * Implementa o fluxo completo de pré-inscrições, validação em linha,
 * upload de documentos e gestão de vagas/lista de espera por turma.
 */
class enrollmentController extends Controller
{
    /**
     * Exibe a listagem completa de candidaturas e inscrições.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtém todas as inscrições carregando antecipadamente as relações com Curso e Turma
        $enrollments = Enrollment::with(['course', 'room'])
            ->latest()
            ->get();

        return view('admin.enrollment.list.index', compact('enrollments'));
    }

    /**
     * Exibe o formulário de registo de nova candidatura.
     * Na fase inicial, o candidato apenas seleciona o Curso e o Turno pretendido.
     * A turma só é atribuída após o pagamento da propina e efetivação da matrícula.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Carrega cursos ativos disponíveis ordenados alfabeticamente
        $courses = Course::orderBy('name', 'asc')->get();

        return view('admin.enrollment.create.index', compact('courses'));
    }

    /**
     * Valida e armazena uma nova candidatura na base de dados.
     * O estado inicial é sempre 'Pendente' e sem turma associada até haver pagamento.
     *
     * @param \Illuminate\Http\Request $request Dados da requisição HTTP
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validação em linha dos dados pessoais, curso e turno pretendido
        $validatedData = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'phone'              => 'required|string|max:20',
            'number_of_identify' => 'required|string|max:50',
            'course_id'          => 'required|exists:courses,id',
            'shift'              => 'required|string|in:Manhã,Tarde,Pós-Laboral',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ], [
            'name.required'               => 'O nome completo do candidato é obrigatório.',
            'name.max'                    => 'O nome não pode exceder os 255 caracteres.',
            'email.required'              => 'O endereço de email é obrigatório.',
            'email.email'                 => 'Introduza um endereço de email válido.',
            'phone.required'              => 'O número de telefone é obrigatório.',
            'phone.max'                   => 'O telefone não pode exceder 20 dígitos.',
            'number_of_identify.required' => 'O número do BI ou Passaporte é obrigatório.',
            'number_of_identify.max'      => 'O documento não pode exceder 50 caracteres.',
            'course_id.required'          => 'A seleção do curso pretendido é obrigatória.',
            'course_id.exists'            => 'O curso selecionado não é válido.',
            'shift.required'              => 'O turno pretendido é obrigatório.',
            'shift.in'                    => 'Selecione um turno válido (Manhã, Tarde ou Pós-Laboral).',
            'image.image'                 => 'O ficheiro anexado deve ser uma imagem válida.',
            'image.mimes'                 => 'A imagem deve estar no formato JPG, PNG ou WEBP.',
            'image.max'                   => 'A imagem não pode exceder o tamanho máximo de 4MB.',
        ]);

        // 2. Processamento e Armazenamento da Imagem de Identificação
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $uploadedImage = $request->file('image');
            $extension     = $uploadedImage->extension();
            $fileName      = md5($uploadedImage->getClientOriginalName() . time()) . '.' . $extension;
            $validatedData['image'] = $uploadedImage->storeAs('candidates', $fileName, 'public');
        } else {
            $validatedData['image'] = null;
        }

        // 3. Regra de Negócio: Candidatura nasce Pendente e sem turma (atribuição ocorre pós-pagamento)
        $validatedData['room_id'] = null;
        $validatedData['status']  = 'Pendente';
        $validatedData['date']    = now();

        // 4. Criação do registo da candidatura na base de dados
        $enrollment = Enrollment::create($validatedData);

        return redirect()
            ->route('enrollment.index')
            ->with('success', 'Candidatura registada com sucesso (' . $enrollment->formattedCode() . '). Aguarda pagamento para atribuição de turma.');
    }

    /**
     * Exibe o perfil detalhado da candidatura, incluindo dados da turma e pagamentos.
     *
     * @param int $id Identificador primário da inscrição
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Carrega a candidatura com as respetivas relações
        $enrollment = Enrollment::with([
            'course',
            'room.classroom',
            'room.teacher',
            'payments',
            'student'
        ])->findOrFail($id);

        return view('admin.enrollment.details.index', compact('enrollment'));
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
        // Procura a inscrição existente
        $enrollment = Enrollment::findOrFail($id);

        // Carrega cursos ativos disponíveis
        $courses = Course::orderBy('name', 'asc')->get();

        return view('admin.enrollment.edit.index', compact('enrollment', 'courses'));
    }

    /**
     * Atualiza os dados de uma candidatura existente na base de dados.
     *
     * @param \Illuminate\Http\Request $request Dados da requisição HTTP
     * @param int $id Identificador primário da inscrição
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);

        // 1. Validação em linha dos campos editáveis da candidatura
        $validatedData = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'phone'              => 'required|string|max:20',
            'number_of_identify' => 'required|string|max:50',
            'course_id'          => 'required|exists:courses,id',
            'shift'              => 'required|string|in:Manhã,Tarde,Pós-Laboral',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ], [
            'name.required'               => 'O nome completo do candidato é obrigatório.',
            'name.max'                    => 'O nome não pode exceder os 255 caracteres.',
            'email.required'              => 'O endereço de email é obrigatório.',
            'email.email'                 => 'Introduza um endereço de email válido.',
            'phone.required'              => 'O número de telefone é obrigatório.',
            'phone.max'                   => 'O telefone não pode exceder 20 dígitos.',
            'number_of_identify.required' => 'O número do BI ou Passaporte é obrigatório.',
            'number_of_identify.max'      => 'O documento não pode exceder 50 caracteres.',
            'course_id.required'          => 'A seleção do curso pretendido é obrigatória.',
            'course_id.exists'            => 'O curso selecionado não é válido.',
            'shift.required'              => 'O turno pretendido é obrigatório.',
            'shift.in'                    => 'Selecione um turno válido (Manhã, Tarde ou Pós-Laboral).',
            'image.image'                 => 'O ficheiro anexado deve ser uma imagem válida.',
            'image.mimes'                 => 'A imagem deve estar no formato JPG, PNG ou WEBP.',
            'image.max'                   => 'A imagem não pode exceder o tamanho máximo de 4MB.',
        ]);

        // 2. Substituição da Imagem (caso um novo ficheiro tenha sido enviado)
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Elimina a imagem antiga do disco, se existir
            if ($enrollment->image && Storage::disk('public')->exists($enrollment->image)) {
                Storage::disk('public')->delete($enrollment->image);
            }

            $uploadedImage = $request->file('image');
            $extension     = $uploadedImage->extension();
            $fileName      = md5($uploadedImage->getClientOriginalName() . time()) . '.' . $extension;
            $validatedData['image'] = $uploadedImage->storeAs('candidates', $fileName, 'public');
        }

        // 3. Atualização dos dados no modelo
        $enrollment->update($validatedData);

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

        // Executa eliminação lógica
        $enrollment->delete();

        return redirect()
            ->route('enrollment.index')
            ->with('success', 'Candidatura eliminada com sucesso!');
    }
}
