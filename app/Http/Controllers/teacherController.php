<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

/**
 * Controlador RESTful para gerir docentes (professores) da instituição.
 *
 * Implementa os sete métodos de recurso padrão do Laravel:
 * index, create, store, show, edit, update e destroy.
 *
 * A ligação de modelos de rotas (route model binding) injeta automaticamente a
 * instância do modelo nos métodos show, edit, update e destroy.
 */
class teacherController extends Controller
{
    /**
     * Lista todos os docentes cadastrados.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        // Devolve todos os registos da tabela teachers.
        return Teacher::all();
    }

    /**
     * Exibe o formulário para criar um novo docente (stub).
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
     * Armazena um novo docente na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os dados do docente
     * @return \App\Models\Teacher
     */
    public function store(Request $request)
    {
        // Valida os campos do docente:
        // name (string), email (email válido e único na tabela teachers),
        // gender (string), specialization (string), number_of_identify (string e único),
        // phone (inteiro) e image (string opcional).
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:teachers',
            'gender' => 'required|string',
            'specialization' => 'required|string',
            'number_of_identify' => 'required|string|unique:teachers',
            'phone' => 'required|integer',
            'image' => 'nullable|string',
        ]);

        return Teacher::create($validated);
    }

    /**
     * Exibe os detalhes de um docente específico.
     *
     * O modelo é injetado automaticamente pelo route model binding do Laravel.
     *
     * @param  \App\Models\Teacher  $teacher Docente a ser exibido
     * @return \App\Models\Teacher
     */
    public function show(Teacher $teacher)
    {
        // Devolve o registo do docente encontrado pelo binding de rota.
        return $teacher;
    }

    /**
     * Exibe o formulário para editar um docente existente (stub).
     *
     * @param  \App\Models\Teacher  $teacher Docente a ser editado
     * @return \App\Models\Teacher
     */
    public function edit(Teacher $teacher)
    {
        // Retorna o recurso para edição (stub — sem view de formulário neste projeto).
        return $teacher;
    }

    /**
     * Atualiza os dados de um docente existente.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os novos dados
     * @param  \App\Models\Teacher  $teacher Docente a ser atualizado
     * @return \App\Models\Teacher
     */
    public function update(Request $request, Teacher $teacher)
    {
        // sometimes| aplica cada regra apenas quando o campo estiver presente.
        // unique:teachers,email,{$teacher->id} ignora o próprio registo na verificação
        // de unicidade do email (mesmo para number_of_identify).
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:teachers,email,' . $teacher->id,
            'gender' => 'sometimes|string',
            'specialization' => 'sometimes|string',
            'number_of_identify' => 'sometimes|string|unique:teachers,number_of_identify,' . $teacher->id,
            'phone' => 'sometimes|integer',
            'image' => 'nullable|string',
        ]);

        // Persiste as alterações apenas nos campos enviados na requisição.
        $teacher->update($validated);

        return $teacher;
    }

    /**
     * Remove um docente da base de dados.
     *
     * Com o trait SoftDeletes no modelo, a remoção é lógica (coluna deleted_at).
     *
     * @param  \App\Models\Teacher  $teacher Docente a ser removido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return response()->noContent();
    }
}
