<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;

/**
 * =========================================================================================
 * CONTROLADOR: Inscrições e Registo de Candidatos (enrollmentController)
 * =========================================================================================
 * Este controlador é responsável por gerir todo o ciclo de vida das inscrições e pré-candidaturas.
 * 
 * Principais Funcionalidades:
 *  - Validação de BI Único por Candidato e Inscrição Única por Curso.
 *  - Criação Automática do Estudante (código de estudante sequencial único: 1001, 1002...).
 *  - Upload e Gestão de Fotografia do Formando (`img/student`).
 *  - Atribuição do Estado Inicial da Inscrição como Pendente (status = 0).
 */
class enrollmentController extends Controller
{
    /**
     * Exibe a listagem de todas as inscrições registadas no sistema.
     * Carrega antecipadamente (Eager Loading) os relacionamentos 'student' e 'course' para evitar consultas N+1.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtém todas as inscrições ordenadas de forma decrescente pelo ID
        $enrollments = Enrollment::with(['student', 'course'])->orderBy('id', 'desc')->get();
        
        // Retorna a vista de listagem com a coleção de inscrições
        return view('admin.enrollment.list.index', ['enrollments' => $enrollments]);
    }

    /**
     * Exibe o formulário de registo de nova inscrição de candidato.
     * Procura os cursos ativos (status = 1) para seleção na lista suspensa do formulário.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Procura apenas os cursos que estão ativos no sistema
        $courses = Course::where('status', 1)->get();

        // Renderiza a vista de criação de inscrição passando os cursos disponíveis
        return view('admin.enrollment.create.index', [
            'courses' => $courses,
        ]);
    }

    /**
     * Valida os dados do candidato e da inscrição, garante BI único por candidato,
     * cria/atualiza o estudante e regista a inscrição com estado inicial Pendente (status = 0).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // ---------------------------------------------------------------------------------
        // 1. VALIDAÇÃO DOS DADOS DO CANDIDATO E INSCRIÇÃO
        // ---------------------------------------------------------------------------------
        $validatedData = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'identity_card_number' => 'required|string|max:255',
            'gender'               => 'nullable|string|in:Masculino,Feminino,Outro',
            'phone'                => 'required|string|max:20',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'course_id'            => 'required|exists:courses,id',
            'date'                 => 'required|date',
            'status'               => 'nullable|boolean',
        ], [
            'name.required'                 => 'O nome do candidato é de preenchimento obrigatório.',
            'email.required'                => 'O e-mail é de preenchimento obrigatório.',
            'email.email'                   => 'Insira um endereço de e-mail válido.',
            'identity_card_number.required' => 'O número do bilhete de identidade é de preenchimento obrigatório.',
            'phone.required'                => 'O número de telefone é de preenchimento obrigatório.',
            'image.image'                   => 'O ficheiro selecionado deve ser uma imagem válida.',
            'image.mimes'                   => 'A imagem deve estar no formato JPG, JPEG, PNG ou WEBP.',
            'image.max'                     => 'A imagem não pode ter um tamanho superior a 2MB.',
            'course_id.required'            => 'Por favor selecione o curso pretendido.',
            'course_id.exists'              => 'O curso selecionado é inválido ou não existe.',
            'date.required'                 => 'A data da inscrição é de preenchimento obrigatório.',
        ]);

        // ---------------------------------------------------------------------------------
        // 2. VERIFICAÇÃO DE BI EXISTENTE E INSCRIÇÃO DUPLICADA NO MESMO CURSO
        // ---------------------------------------------------------------------------------
        $student = Student::where('identity_card_number', $validatedData['identity_card_number'])->first();

        if ($student) {
            // Impede a inscrição duplicada no mesmo curso para o mesmo candidato
            $existingEnrollment = Enrollment::where('student_id', $student->id)
                ->where('course_id', $validatedData['course_id'])
                ->first();

            if ($existingEnrollment) {
                return back()->withInput()->withErrors([
                    'identity_card_number' => 'O candidato com o BI "' . $validatedData['identity_card_number'] . '" já se encontra inscrito neste curso.'
                ]);
            }

            // Atualiza o contacto telefónico e género do formando se o candidato já existir
            $student->update([
                'phone_number' => $validatedData['phone'],
                'gender'       => $validatedData['gender'] ?? $student->gender,
            ]);
        } else {
            // -----------------------------------------------------------------------------
            // 3. UPLOAD DE FOTOGRAFIA E REGISTO DE NOVO ESTUDANTE
            // -----------------------------------------------------------------------------
            $imagePath = null;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $requestImage = $request->file('image');
                $extension = $requestImage->getClientOriginalExtension() ?: $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
                $imagePath = $requestImage->storeAs('img/student', $imageName, 'public');
            }

            // Gera o código numérico sequencial do estudante (ex: 1001, 1002...)
            $maxCode = Student::max('code');
            $studentCode = $maxCode ? ($maxCode + 1) : 1001;

            // Regista o novo estudante
            $student = Student::create([
                'name'                 => $validatedData['name'],
                'email'                => $validatedData['email'],
                'identity_card_number' => $validatedData['identity_card_number'],
                'gender'               => $validatedData['gender'] ?? null,
                'phone_number'         => $validatedData['phone'],
                'code'                 => $studentCode,
                'image'                => $imagePath,
            ]);
        }

        // ---------------------------------------------------------------------------------
        // 4. CRIAÇÃO DA INSCRIÇÃO COM ESTADO INICIAL PENDENTE (status = 0)
        // ---------------------------------------------------------------------------------
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id'  => $validatedData['course_id'],
            'date'        => $validatedData['date'],
            'status'      => 0, // Estado automático inicial: Pendente
        ]);

        // Redireciona para a listagem principal com mensagem de sucesso
        return redirect()->route('enrollment.index')->with('success', 'Inscrição de candidato efetuada com sucesso!');
    }

    /**
     * Exibe a vista com os detalhes completos de uma inscrição específica.
     * Inclui a informação do formando (foto, BI, e-mail, telefone) e do curso associado.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura a inscrição por ID ou devolve erro 404 se não for encontrada
        $enrollment = Enrollment::with(['student', 'course'])->findOrFail($id);

        // Renderiza a vista de detalhes
        return view('admin.enrollment.details.index', ['enrollment' => $enrollment]);
    }

    /**
     * Exibe o formulário para editar uma inscrição existente.
     * Pré-carrega a foto, dados do candidato e lista de cursos ativos para alteração.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Procura a inscrição com o estudante e curso associados
        $enrollment = Enrollment::with(['student', 'course'])->findOrFail($id);

        // Obtém todos os cursos ativos para permitir alteração de curso
        $courses    = Course::where('status', 1)->get();

        // Renderiza a vista de edição
        return view('admin.enrollment.edit.index', [
            'enrollment' => $enrollment,
            'courses'    => $courses,
        ]);
    }

    /**
     * Valida e atualiza os dados do formando (incluindo fotografia) e a inscrição na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Carrega a inscrição a ser atualizada
        $enrollment = Enrollment::with('student')->findOrFail($id);

        // Validação dos dados alterados no formulário de edição
        $validatedData = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'identity_card_number' => 'required|string|max:255',
            'gender'               => 'nullable|string|in:Masculino,Feminino,Outro',
            'phone'                => 'required|string|max:20',
            'course_id'            => 'required|exists:courses,id',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'                 => 'O nome do candidato é obrigatório.',
            'email.required'                => 'O email é obrigatório.',
            'email.email'                   => 'Insira um endereço de e-mail válido.',
            'identity_card_number.required' => 'O número do bilhete de identidade é obrigatório.',
            'phone.required'                => 'O número de telefone é obrigatório.',
            'course_id.required'            => 'Por favor selecione o curso.',
            'course_id.exists'              => 'O curso selecionado é inválido.',
            'image.image'                   => 'O ficheiro selecionado deve ser uma imagem.',
            'image.mimes'                   => 'A imagem deve estar no formato JPG, JPEG, PNG ou WEBP.',
            'image.max'                     => 'A imagem não pode ter um tamanho superior a 2MB.',
        ]);

        $student = $enrollment->student;

        if ($student) {
            // Processa a substituição da fotografia de perfil se um novo ficheiro tiver sido submetido
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Elimina a foto antiga do disco de armazenamento se ela existir
                if ($student->image && Storage::disk('public')->exists($student->image)) {
                    Storage::disk('public')->delete($student->image);
                }

                // Armazena a nova imagem enviada
                $requestImage = $request->file('image');
                $extension = $requestImage->getClientOriginalExtension() ?: $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
                $imagePath = $requestImage->storeAs('img/student', $imageName, 'public');

                $student->image = $imagePath;
            }

            // Atualiza os atributos do estudante
            $student->name                 = $validatedData['name'];
            $student->email                = $validatedData['email'];
            $student->identity_card_number = $validatedData['identity_card_number'];
            $student->gender               = $validatedData['gender'] ?? $student->gender;
            $student->phone_number         = $validatedData['phone'];
            $student->save();
        }

        // Atualiza o curso associado à inscrição
        $enrollment->update([
            'course_id' => $validatedData['course_id'],
        ]);

        // Redireciona para a listagem de inscrições com mensagem de sucesso
        return redirect()->route('enrollment.index')->with('success', 'Inscrição atualizada com sucesso!');
    }

    /**
     * Remove uma inscrição da base de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Localiza a inscrição e procede com a sua eliminação
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        // Redireciona com mensagem de confirmação
        return redirect()->route('enrollment.index')->with('success', 'Inscrição eliminada com sucesso!');
    }
}

