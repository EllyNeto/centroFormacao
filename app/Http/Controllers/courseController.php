<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

/**
 * Controlador responsável pela gestão de CRUD de Cursos.
 */
class courseController extends Controller
{
    /**
     * Exibe a listagem de todos os cursos registados na base de dados.
     */
    public function index()
    {
        // Procura todos os cursos registados na base de dados
        $courses = Course::all();

        // Renderiza a view da tabela de cursos enviando o array ['courses' => $courses]
        return view('admin.course.list.index', ['courses' => $courses]);
    }

    /**
     * Exibe o formulário de criação de um novo curso.
     */
    public function create()
    {
        return view('admin.course.create.index');
    }

    /**
     * Valida e guarda um novo curso na base de dados.
     */
    public function store(Request $request)
    {
        // Formata o campo 'value' removendo pontos de milhar e formatando a vírgula decimal
        if ($request->has('value')) {
            $cleanValue = str_replace(['.', ' '], '', $request->input('value'));
            $cleanValue = str_replace(',', '.', $cleanValue);
            $request->merge(['value' => $cleanValue]);
        }

        // Validação rigorosa dos dados recebidos no formulário
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'duration'    => 'required|integer|min:1',
            'value'       => 'required|numeric|min:0',
            'status'      => 'required|boolean',
            'description' => 'required|string',
        ]);

        // Cria o registo do novo curso na base de dados
        Course::create($validated);

        // Redirecciona para a listagem com mensagem de sucesso
        return redirect()->route('course.index')->with('success', 'Curso criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um curso específico pelo ID.
     */
    public function show($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.course.details.index', ['course' => $course]);
    }

    /**
     * Exibe o formulário para edição de um curso existente.
     */
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.course.edit.index', ['course' => $course]);
    }

    /**
     * Valida e actualiza os dados de um curso existente na base de dados.
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // Sanitização dos separadores de milhar do valor monetário
        if ($request->has('value')) {
            $cleanValue = str_replace(['.', ' '], '', $request->input('value'));
            $cleanValue = str_replace(',', '.', $cleanValue);
            $request->merge(['value' => $cleanValue]);
        }

        // Validação dos campos editados
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'duration'    => 'required|integer|min:1',
            'value'       => 'required|numeric|min:0',
            'status'      => 'required|boolean',
            'description' => 'required|string',
        ]);

        // Actualiza o registo na base de dados
        $course->update($validated);

        return redirect()->route('course.index')->with('success', 'Curso actualizado com sucesso!');
    }

    /**
     * Remove (Soft Delete) um curso da base de dados.
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('course.index')->with('success', 'Curso eliminado com sucesso!');
    }
}
