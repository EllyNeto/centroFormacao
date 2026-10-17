<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Controlador responsável pela gestão de Formadores (Professores) do Centro de Formação.
 * 
 * Modulo 3: Formadores
 * Funcionalidades:
 * - Listagem de formadores registados com contagem de turmas associadas.
 * - Registo de novos formadores com upload de fotografia de perfil.
 * - Validação em linha de email único e número de BI (Bilhete de Identidade) único.
 * - Edição de dados e substituição segura de fotografia.
 * - Proteção de Soft Delete contra eliminação de formadores associados a turmas ativas.
 */
class teacherController extends Controller
{
    /**
     * Exibe a listagem de todos os formadores registados.
     * Carrega a contagem de turmas associadas a cada formador para exibição na vista.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todos os formadores não eliminados, ordenados do mais recente para o mais antigo,
        // incluindo a contagem de turmas associadas (rooms_count).
        $teachers = Teacher::withCount('rooms')
            ->latest()
            ->get();

        // Retorna a vista de listagem com a coleção de formadores
        return view('admin.teacher.list.index', compact('teachers'));
    }

    /**
     * Exibe o formulário de registo de um novo formador.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retorna a vista com o formulário de criação de formadores
        return view('admin.teacher.create.index');
    }

    /**
     * Armazena um novo formador na base de dados após validação rigorosa dos dados enviados.
     * Trata do upload da imagem de perfil e valida a unicidade do Email e BI.
     *
     * @param \Illuminate\Http\Request $request Dados da requisição HTTP
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação direta em linha (inline validation) com mensagens personalizadas em português.
        // Garante que email e number_of_identify sejam únicos entre registos ativos (deleted_at IS NULL).
        $validatedData = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => [
                'required',
                'email',
                'max:255',
                Rule::unique('teachers', 'email')->whereNull('deleted_at'),
            ],
            'specialization'     => 'required|string|max:255',
            'number_of_identify' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teachers', 'number_of_identify')->whereNull('deleted_at'),
            ],
            'phone'              => 'required|string|max:50',
            'gender'             => 'required|string|in:Masculino,Feminino',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ], [
            // Mensagens de erro personalizadas para o utilizador
            'name.required'               => 'O campo nome completo é obrigatório.',
            'name.string'                 => 'O nome deve ser um texto válido.',
            'name.max'                    => 'O nome não pode ter mais de 255 caracteres.',
            'email.required'              => 'O campo email é obrigatório.',
            'email.email'                 => 'Introduza um endereço de email válido.',
            'email.unique'                => 'Este endereço de email já se encontra registado para outro formador.',
            'specialization.required'     => 'O campo especialização é obrigatório.',
            'number_of_identify.required' => 'O número do Bilhete de Identidade (BI) é obrigatório.',
            'number_of_identify.unique'   => 'Este número de Bilhete de Identidade (BI) já se encontra registado.',
            'phone.required'              => 'O número de telefone é obrigatório.',
            'gender.required'             => 'Selecione o género do formador.',
            'gender.in'                   => 'O género selecionado é inválido.',
            'image.image'                 => 'O ficheiro selecionado deve ser uma imagem.',
            'image.mimes'                 => 'A imagem deve ser do formato: jpeg, png, jpg, gif, svg ou webp.',
            'image.max'                   => 'A imagem não pode ter um tamanho superior a 4MB.',
        ]);

        // Processamento do upload da fotografia de perfil caso seja fornecida
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image');
            $extension = $requestImage->extension();
            // Gera um nome único baseado na hash do nome original e timestamp
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            // Armazena a imagem na pasta 'public/teachers' no disco de armazenamento
            $validatedData['image'] = $requestImage->storeAs('teachers', $imageName, 'public');
        } else {
            $validatedData['image'] = null;
        }

        // Cria o registo do formador na base de dados
        $teacher = Teacher::create($validatedData);

        // Envio de notificação nativa por email de boas-vindas ao novo docente
        try {
            if (!empty($teacher->email)) {
                $teacher->notify(new \App\Notifications\TeacherWelcomeNotification($teacher));
            }
        } catch (\Throwable $e) {
            // Em caso de falha de conexão SMTP, regista em log para diagnóstico sem quebrar o cadastro
            \Illuminate\Support\Facades\Log::warning("Falha ao enviar email de boas-vindas ao formador: " . $e->getMessage());
        }

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()->route('teacher.index')->with('success', 'Formador registado com sucesso!');
    }

    /**
     * Exibe o perfil e os detalhes completos de um formador específico,
     * incluindo as turmas lecionadas e as salas correspondentes.
     *
     * @param int $id Identificador do formador
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura o formador pelo ID juntamente com o histórico de turmas, cursos e salas associados
        $teacher = Teacher::with(['rooms.course', 'rooms.classroom'])->findOrFail($id);

        // Retorna a vista de detalhes do formador
        return view('admin.teacher.details.index', compact('teacher'));
    }

    /**
     * Exibe o formulário para edição das informações do formador.
     *
     * @param int $id Identificador do formador
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Procura o formador ou lança erro 404 se não for encontrado
        $teacher = Teacher::findOrFail($id);

        // Retorna a vista de edição preenchida com os dados atuais
        return view('admin.teacher.edit.index', compact('teacher'));
    }

    /**
     * Atualiza os dados do formador na base de dados.
     * Substitui a imagem anterior se uma nova imagem for enviada.
     *
     * @param \Illuminate\Http\Request $request Dados da requisição
     * @param int $id Identificador do formador
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Procura o formador a ser atualizado
        $teacher = Teacher::findOrFail($id);

        // Validação dos dados. Ignora o ID atual nas regras de unicidade de Email e BI.
        $validatedData = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => [
                'required',
                'email',
                'max:255',
                Rule::unique('teachers', 'email')->ignore($id)->whereNull('deleted_at'),
            ],
            'specialization'     => 'required|string|max:255',
            'number_of_identify' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teachers', 'number_of_identify')->ignore($id)->whereNull('deleted_at'),
            ],
            'phone'              => 'required|string|max:50',
            'gender'             => 'required|string|in:Masculino,Feminino',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ], [
            // Mensagens personalizadas de erro
            'name.required'               => 'O campo nome completo é obrigatório.',
            'email.required'              => 'O campo email é obrigatório.',
            'email.email'                 => 'Introduza um endereço de email válido.',
            'email.unique'                => 'Este endereço de email já se encontra registado por outro formador.',
            'specialization.required'     => 'O campo especialização é obrigatório.',
            'number_of_identify.required' => 'O número do Bilhete de Identidade (BI) é obrigatório.',
            'number_of_identify.unique'   => 'Este número de Bilhete de Identidade (BI) já se encontra registado.',
            'phone.required'              => 'O número de telefone é obrigatório.',
            'gender.required'             => 'Selecione o género do formador.',
            'gender.in'                   => 'O género selecionado é inválido.',
            'image.image'                 => 'O ficheiro selecionado deve ser uma imagem.',
            'image.mimes'                 => 'A imagem deve ser do formato: jpeg, png, jpg, gif, svg ou webp.',
            'image.max'                   => 'A imagem não pode ter um tamanho superior a 4MB.',
        ]);

        // Processamento da nova fotografia caso tenha sido feito um novo upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Apaga a fotografia antiga do disco se existir
            if ($teacher->image && Storage::disk('public')->exists($teacher->image)) {
                Storage::disk('public')->delete($teacher->image);
            }

            // Armazena a nova imagem
            $requestImage = $request->file('image');
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $validatedData['image'] = $requestImage->storeAs('teachers', $imageName, 'public');
        }

        // Atualiza o registo na base de dados
        $teacher->update($validatedData);

        // Redireciona com mensagem de sucesso
        return redirect()->route('teacher.index')->with('success', 'Dados do formador atualizados com sucesso!');
    }

    /**
     * Remove um formador da base de dados (Soft Delete).
     * Impede a eliminação caso o formador lecione turmas ativas (Abertas ou Em curso).
     *
     * @param int $id Identificador do formador
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Procura o formador a ser eliminado
        $teacher = Teacher::findOrFail($id);

        // Regra de Negócio: Impede eliminação se o formador possui turmas ativas vinculadas
        if ($teacher->hasActiveRooms()) {
            return redirect()->route('teacher.index')->with(
                'error',
                'Impossível eliminar o formador: este formador possui turmas ativas (Abertas ou Em curso) atribuídas.'
            );
        }

        // Executa a eliminação lógica (Soft Delete)
        $teacher->delete();

        // Redireciona para a listagem com mensagem afirmativa de eliminação
        return redirect()->route('teacher.index')->with('success', 'Formador eliminado com sucesso!');
    }
}

