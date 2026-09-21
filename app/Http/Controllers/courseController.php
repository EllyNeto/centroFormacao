<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

/**
 * =========================================================================================
 * CONTROLADOR: Gestão de Cursos (courseController)
 * =========================================================================================
 * Este controlador é responsável pela gestão completa das operações CRUD da entidade Curso (Course).
 * Permite criar novos cursos de formação, atualizar carga horária, categorias e alterar estados de ativação.
 */
class courseController extends Controller
{
    /**
     * Exibe a listagem de todos os cursos registados na base de dados.
     * Ordena os cursos do mais recente para o mais antigo.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todos os cursos registados ordenados pelo ID decrescente
        $courses = Course::orderBy('id', 'desc')->get();

        // Retorna a vista de listagem passando a coleção de cursos
        return view('admin.course.list.index', ['courses' => $courses]); 
    }

    /**
     * Exibe o formulário de criação de um novo curso.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retorna a vista com o formulário para registar um novo curso
        return view('admin.course.create.index');
    }

    /**
     * Valida os dados submetidos no formulário e guarda um novo curso na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação rigorosa dos campos recebidos do formulário
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'status'      => 'required|string|max:255',
            'duration'    => 'required|integer|min:1',
            'description' => 'nullable|string',
        ], [
            'name.required'     => 'O nome do curso é de preenchimento obrigatório.',
            'status.required'   => 'Por favor selecione o estado/categoria do curso.',
            'duration.required' => 'A carga horária do curso é de preenchimento obrigatório.',
            'duration.integer'  => 'A duração deve ser um número inteiro de horas.',
            'duration.min'      => 'A duração deve ser de pelo menos 1 hora.',
        ]);

        // Criação do registo do curso na base de dados
        Course::create($validatedData);

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()->route('course.index')->with('success', 'Curso criado com sucesso!');
    }

    /**
     * Exibe a página com os detalhes de um curso específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura o curso pelo ID ou devolve erro 404 se não for encontrado
        $course = Course::findOrFail($id);

        // Retorna a vista de detalhes do curso
        return view('admin.course.details.index', ['course' => $course]);
    }

    /**
     * Exibe o formulário para editar um curso existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Procura o curso pelo ID para pré-preencher o formulário de alteração
        $course = Course::findOrFail($id);

        // Retorna a vista de edição passando os dados do curso
        return view('admin.course.edit.index', ['course' => $course]);
    }

    /**
     * Valida e atualiza os dados de um curso existente na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Localiza o curso a ser atualizado
        $course = Course::findOrFail($id);

        // Validação dos dados submetidos no formulário de edição
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'status'      => 'required|string|max:255',
            'duration'    => 'required|integer|min:1',
            'description' => 'nullable|string',
        ], [
            'name.required'     => 'O nome do curso é obrigatório.',
            'status.required'   => 'Por favor selecione a categoria/estado do curso.',
            'duration.required' => 'A duração do curso é obrigatória.',
            'duration.integer'  => 'A duração deve ser um número inteiro.',
            'duration.min'      => 'A duração deve ser de pelo menos 1 hora.',
        ]);

        // Atualiza os dados do curso na base de dados
        $course->update($validatedData);

        // Redireciona para a listagem com mensagem de confirmação
        return redirect()->route('course.index')->with('success', 'Curso atualizado com sucesso!');
    }

    /**
     * Remove um curso da base de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Procura e remove o registo do curso selecionado
        $course = Course::findOrFail($id);
        $course->delete();

        // Redireciona para a listagem de cursos com mensagem de confirmação
        return redirect()->route('course.index')->with('success', 'Curso eliminado com sucesso!');
    }
}

