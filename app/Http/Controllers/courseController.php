<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

/**
 * Controlador RESTful para gerir cursos da instituição de ensino.
 *
 * Implementa os sete métodos de recurso padrão do Laravel:
 * index, create, store, show, edit, update e destroy.
 *
 * A ligação de modelos de rotas (route model binding) injeta automaticamente a
 * instância do modelo nos métodos show, edit, update e destroy.
 */
class courseController extends Controller
{
    /**
     * Lista todos os cursos cadastrados.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        // Devolve todos os registos da tabela courses.
        return Course::all();
    }

    /**
     * Exibe o formulário para criar um novo curso (stub).
     *
     * Neste projeto a criação é feita por API, sem view de formulário Blade.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Armazena um novo curso na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os dados do curso
     * @return \App\Models\Course
     */
    public function store(Request $request)
    {
        // Valida os campos do curso:
        // name (string obrigatória), description (string opcional),
        // duration (inteiro obrigatório: duração em horas),
        // status (booleano obrigatório: activo/inactivo) e value (numérico: preço).
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'status' => 'required|boolean',
            'value' => 'required|numeric',
        ]);

        return Course::create($validated);
    }

    /**
     * Exibe os detalhes de um curso específico.
     *
     * O modelo é injetado automaticamente pelo route model binding do Laravel.
     *
     * @param  \App\Models\Course  $course Curso a ser exibido
     * @return \App\Models\Course
     */
    public function show(Course $course)
    {
        // Devolve o registo do curso encontrado pelo binding de rota.
        return $course;
    }

    /**
     * Exibe o formulário para editar um curso existente (stub).
     *
     * @param  \App\Models\Course  $course Curso a ser editado
     * @return \App\Models\Course
     */
    public function edit(Course $course)
    {
        // Retorna o recurso para edição (stub — sem view de formulário neste projeto).
        return $course;
    }

    /**
     * Atualiza os dados de um curso existente.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os novos dados
     * @param  \App\Models\Course  $course Curso a ser atualizado
     * @return \App\Models\Course
     */
    public function update(Request $request, Course $course)
    {
        // sometimes| aplica cada regra apenas quando o campo estiver presente,
        // permitindo atualizações parciais do curso.
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'duration' => 'sometimes|integer',
            'status' => 'sometimes|boolean',
            'value' => 'sometimes|numeric',
        ]);

        // Persiste as alterações apenas nos campos enviados na requisição.
        $course->update($validated);

        return $course;
    }

    /**
     * Remove um curso da base de dados.
     *
     * Com o trait SoftDeletes no modelo, a remoção é lógica (coluna deleted_at).
     *
     * @param  \App\Models\Course  $course Curso a ser removido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return response()->noContent();
    }
}
