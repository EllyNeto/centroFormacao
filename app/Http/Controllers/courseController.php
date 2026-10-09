<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

/**
 * Controlador courseController - Gestão da Oferta Formativa (Cursos).
 * Responsável pelas operações CRUD, validações diretas no controlador e regras de proteção dos Cursos.
 */
class courseController extends Controller
{
    /**
     * Exibe a listagem de todos os cursos registados no sistema.
     * Traz a contagem de turmas e de inscrições associadas a cada curso.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todos os cursos ordenados alfabeticamente pelo nome, contando turmas e inscrições vinculadas
        $courses = Course::withCount(['rooms', 'enrollments'])
            ->orderBy('name', 'asc')
            ->get();

        // Renderiza a vista de listagem de cursos
        return view('admin.course.list.index', compact('courses'));
    }

    /**
     * Exibe o formulário de cadastro de um novo curso.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retorna a vista do formulário de criação de curso
        return view('admin.course.create.index');
    }

    /**
     * Valida e armazena um novo curso na base de dados.
     * Realiza a validação direta no controlador garantindo nome único e valores positivos.
     *
     * @param  \Illuminate\Http\Request  $request Dados recebidos do formulário
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // VALIDAÇÃO DIRETA NO CONTROLADOR:
        // Garante que o nome do curso é único (ignorando SoftDeletes), a duração é positiva e o preço é não-negativo.
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255|unique:courses,name,NULL,id,deleted_at,NULL',
            'duration'    => 'required|integer|min:1',
            'value'       => 'required|numeric|min:0',
            'status'      => 'required|boolean',
            'description' => 'nullable|string',
        ], [
            'name.required'     => 'O nome do curso é de preenchimento obrigatório.',
            'name.max'          => 'O nome do curso não pode exceder 255 caracteres.',
            'name.unique'       => 'Já existe um curso registado com esta designação.',
            'duration.required' => 'A carga horária (duração) é de preenchimento obrigatório.',
            'duration.integer'  => 'A carga horária deve ser um valor inteiro de horas.',
            'duration.min'      => 'A carga horária deve ser de pelo menos 1 hora.',
            'value.required'    => 'O preço do curso é de preenchimento obrigatório.',
            'value.numeric'     => 'O preço do curso deve ser um valor numérico válido.',
            'value.min'         => 'O preço do curso não pode ser um valor negativo.',
            'status.required'   => 'O estado de atividade do curso é obrigatório.',
            'status.boolean'    => 'O estado do curso deve ser Ativo ou Inativo.',
        ]);

        // Cria o registo do novo curso na base de dados
        Course::create($validatedData);

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()
            ->route('course.index')
            ->with('success', 'Curso criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um curso específico.
     * Apresenta as informações gerais do curso e a lista de turmas alocadas.
     *
     * @param  int  $id Identificador primário do curso
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura o curso pelo ID carregando as turmas associadas com seus formadores e salas
        $course = Course::with(['rooms.teacher', 'rooms.classroom', 'enrollments'])->findOrFail($id);

        // Retorna a vista de detalhes do curso
        return view('admin.course.details.index', compact('course'));
    }

    /**
     * Exibe o formulário para edição de um curso existente.
     *
     * @param  int  $id Identificador primário do curso
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Procura o curso pelo ID ou lança exceção 404
        $course = Course::findOrFail($id);

        // Retorna a vista do formulário de edição
        return view('admin.course.edit.index', compact('course'));
    }

    /**
     * Valida e atualiza os dados de um curso existente na base de dados.
     * Verifica permissão para alterar estado caso existam turmas ou inscrições ativas.
     *
     * @param  \Illuminate\Http\Request  $request Dados recebidos do formulário
     * @param  int  $id Identificador primário do curso
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Procura o curso pelo ID
        $course = Course::findOrFail($id);

        // VALIDAÇÃO DIRETA NO CONTROLADOR:
        // Valida ignorando a verificação de unicidade para o próprio curso a editar
        $validatedData = $request->validate([
            'name'        => "required|string|max:255|unique:courses,name,{$id},id,deleted_at,NULL",
            'duration'    => 'required|integer|min:1',
            'value'       => 'required|numeric|min:0',
            'status'      => 'required|boolean',
            'description' => 'nullable|string',
        ], [
            'name.required'     => 'O nome do curso é de preenchimento obrigatório.',
            'name.max'          => 'O nome do curso não pode exceder 255 caracteres.',
            'name.unique'       => 'Já existe outro curso registado com esta designação.',
            'duration.required' => 'A carga horária (duração) é de preenchimento obrigatório.',
            'duration.integer'  => 'A carga horária deve ser um valor inteiro de horas.',
            'duration.min'      => 'A carga horária deve ser de pelo menos 1 hora.',
            'value.required'    => 'O preço do curso é de preenchimento obrigatório.',
            'value.numeric'     => 'O preço do curso deve ser um valor numérico válido.',
            'value.min'         => 'O preço do curso não pode ser um valor negativo.',
            'status.required'   => 'O estado de atividade do curso é obrigatório.',
            'status.boolean'    => 'O estado do curso deve ser Ativo ou Inativo.',
        ]);

        // REGRA DE NEGÓCIO 1: Proteção na Desativação do Curso
        // Se a requisição tentar mudar o estado para Inativo (false/0), verifica se há turmas ou inscrições ativas
        $newStatus = (bool) $validatedData['status'];
        if ($course->status && !$newStatus) {
            if ($course->hasActiveRooms() || $course->hasActiveEnrollments()) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'status' => 'Não é possível desativar este curso enquanto existirem turmas ou inscrições ativas vinculadas a ele.'
                    ]);
            }
        }

        // Atualiza o registo do curso na base de dados
        $course->update($validatedData);

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()
            ->route('course.index')
            ->with('success', 'Curso atualizado com sucesso!');
    }

    /**
     * Remove (Soft Delete) um curso da base de dados.
     * Aplica a regra de proteção impedindo a eliminação de cursos com turmas ou inscrições ativas.
     *
     * @param  int  $id Identificador primário do curso
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Procura o curso pelo ID
        $course = Course::findOrFail($id);

        // REGRA DE NEGÓCIO 2: Impedir Eliminação de Curso em Uso
        // Se o curso possuir turmas ativas ou inscrições ativas, bloqueia a eliminação
        if ($course->hasActiveRooms() || $course->hasActiveEnrollments()) {
            return back()->with('error', 'Impossível eliminar o curso: existem turmas ou inscrições ativas associadas a esta formação.');
        }

        // Executa a eliminação lógica
        $course->delete();

        // Redireciona com mensagem de sucesso
        return redirect()
            ->route('course.index')
            ->with('success', 'Curso eliminado com sucesso!');
    }
}
