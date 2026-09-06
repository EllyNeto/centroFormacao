<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\_Class;
use App\Models\Course;
use App\Models\Teacher;

/**
 * Controlador responsável pela gestão dinâmica e completa das operações CRUD da entidade Turma (_Class).
 */
class classController extends Controller
{
    /**
     * Exibe a listagem dinâmica de todas as turmas registadas na base de dados.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todas as turmas registadas na base de dados ordenadas pelo ID decrescente (mais recentes primeiro)
        $classes = _Class::orderBy('id', 'desc')->get();

        // Retorna a vista de listagem passando a coleção dinâmica de turmas
        return view('admin.class.list.index', ['classes' => $classes]);
    }

    /**
     * Exibe o formulário para registar uma nova turma com os cursos e formadores dinâmicos.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Procura os cursos e formadores activos para popular os dropdowns de seleção
        $courses = Course::where('status', 1)->get();
        $teachers = Teacher::where('status', 1)->get();

        // Retorna a vista contendo o formulário de criação de turma
        return view('admin.class.create.index', [
            'courses' => $courses,
            'teachers' => $teachers,
        ]);
    }

    /**
     * Valida os dados submetidos pelo formulário e guarda uma nova turma na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação dos dados recebidos do formulário de criação de turma
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'nullable|string|max:100',
            'course_name'  => 'nullable|string|max:255',
            'teacher_name' => 'nullable|string|max:255',
            'room'         => 'nullable|string|max:100',
            'shift'        => 'required|string|max:50',
            'capacity'     => 'required|integer|min:1',
            'status'       => 'required|boolean',
        ], [
            'name.required'     => 'O nome da turma é obrigatório.',
            'name.max'          => 'O nome da turma não pode exceder 255 carateres.',
            'shift.required'    => 'Por favor escolha o turno da turma.',
            'capacity.required' => 'A capacidade da turma é obrigatória.',
            'capacity.integer'  => 'A capacidade deve ser um número inteiro.',
            'capacity.min'      => 'A capacidade deve ser de pelo menos 1 aluno.',
            'status.required'   => 'Por favor selecione o estado da turma.',
        ]);

        // Se o código da turma não for preenchido, gera automaticamente um código com base no ano
        if (empty($validatedData['code'])) {
            $validatedData['code'] = 'TURMA-' . date('Y') . '-' . rand(100, 999);
        }

        // Criação dinâmica do registo na tabela 'classes'
        _Class::create($validatedData);

        // Redireciona a navegação para a listagem principal com mensagem de sucesso armazenada na sessão
        return redirect()->route('class.index')->with('success', 'Turma registada com sucesso!');
    }

    /**
     * Exibe a página de detalhes de uma turma dinâmica específica pelo ID.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura dinamicamente a turma na base de dados pelo ID
        $class = _Class::findOrFail($id);

        // Retorna a vista de detalhes passando o objeto da turma
        return view('admin.class.show.index', ['class' => $class]);
    }

    /**
     * Exibe o formulário de edição para alterar os dados de uma turma existente na base de dados.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Procura a turma na base de dados pelo ID
        $class = _Class::findOrFail($id);

        // Procura os cursos e formadores activos para seleção
        $courses = Course::where('status', 1)->get();
        $teachers = Teacher::where('status', 1)->get();

        // Retorna a vista de edição passando o registo dinâmico da turma
        return view('admin.class.edit.index', [
            'class' => $class,
            'courses' => $courses,
            'teachers' => $teachers,
        ]);
    }

    /**
     * Valida e atualiza os dados de uma turma existente na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Procura a turma a ser atualizada
        $class = _Class::findOrFail($id);

        // Validação dos dados submetidos no formulário de edição da turma
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'nullable|string|max:100',
            'course_name'  => 'nullable|string|max:255',
            'teacher_name' => 'nullable|string|max:255',
            'room'         => 'nullable|string|max:100',
            'shift'        => 'required|string|max:50',
            'capacity'     => 'required|integer|min:1',
            'status'       => 'required|boolean',
        ], [
            'name.required'     => 'O nome da turma é obrigatório.',
            'name.max'          => 'O nome da turma não pode exceder 255 carateres.',
            'shift.required'    => 'Por favor escolha o turno da turma.',
            'capacity.required' => 'A capacidade da turma é obrigatória.',
            'capacity.integer'  => 'A capacidade deve ser um número inteiro.',
            'capacity.min'      => 'A capacidade deve ser de pelo menos 1 aluno.',
            'status.required'   => 'Por favor selecione o estado da turma.',
        ]);

        // Atualização dos campos na base de dados
        $class->update($validatedData);

        // Redireciona para a listagem com mensagem de confirmação
        return redirect()->route('class.index')->with('success', 'Turma atualizada com sucesso!');
    }

    /**
     * Remove uma turma da base de dados (utilizando SoftDeletes).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Procura a turma pelo ID
        $class = _Class::findOrFail($id);

        // Executa a eliminação suave (soft delete) da turma
        $class->delete();

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()->route('class.index')->with('success', 'Turma eliminada com sucesso!');
    }
}
