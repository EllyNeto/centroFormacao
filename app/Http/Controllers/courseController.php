<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

/**
 * Controlador responsável pela gestão das operações CRUD de Cursos (Course).
 */
class courseController extends Controller
{
    /**
     * Exibe a listagem de todos os cursos registados na base de dados.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todos os cursos registados ordenados pelo ID decrescente
        $courses = Course::orderBy('id', 'desc')->get();

        // Retorna a vista de listagem passando a coleção de cursos
        return view('course.index.index', ['courses' => $courses]);
    }

    /**
     * Exibe o formulário de criação de um novo curso.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retorna a vista com o formulário para registar um novo curso
        return view('course.create.index');
    }

    /**
     * Valida os dados submetidos e guarda um novo curso na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação dos dados recebidos do formulário
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'duration'    => 'required|integer|min:1',
            'description' => 'nullable|string',
        ], [
            'name.required'     => 'O nome do curso é obrigatório.',
            'category.required' => 'Por favor selecione a categoria do curso.',
            'duration.required' => 'A duração do curso é obrigatória.',
            'duration.integer'  => 'A duração deve ser um número inteiro.',
            'duration.min'      => 'A duração deve ser de pelo menos 1 hora.',
        ]);

        // Criação do registo na base de dados com os dados validados
        Course::create($validatedData);

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()->route('course.index')->with('success', 'Curso criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um curso específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura o curso pelo ID ou lança erro 404 se não for encontrado
        $course = Course::findOrFail($id);

        // Retorna a vista de detalhes do curso
        return view('course.show.index', ['course' => $course]);
    }

    /**
     * Exibe o formulário de edição para um curso existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Procura o curso pelo ID para pré-preencher o formulário
        $course = Course::findOrFail($id);

        // Retorna a vista de edição passando os dados do curso
        return view('course.edit.index', ['course' => $course]);
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
        // Procura o curso a ser atualizado
        $course = Course::findOrFail($id);

        // Validação dos dados submetidos no formulário de edição
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'duration'    => 'required|integer|min:1',
            'description' => 'nullable|string',
        ], [
            'name.required'     => 'O nome do curso é obrigatório.',
            'category.required' => 'Por favor selecione a categoria do curso.',
            'duration.required' => 'A duração do curso é obrigatória.',
            'duration.integer'  => 'A duração deve ser um número inteiro.',
            'duration.min'      => 'A duração deve ser de pelo menos 1 hora.',
        ]);

        // Atualização das propriedades do curso na base de dados
        $course->update($validatedData);

        // Redireciona para a listagem com mensagem de sucesso
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
        // Procura o curso pelo ID
        $course = Course::findOrFail($id);

        // Apaga o registo do curso
        $course->delete();

        // Redireciona para a listagem com mensagem de aviso/sucesso
        return redirect()->route('course.index')->with('success', 'Curso eliminado com sucesso!');
    }
}
