<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

/**
 * Controlador RESTful para gerir matrículas (enrollments) de formandos em cursos.
 *
 * Implementa os sete métodos de recurso padrão do Laravel:
 * index, create, store, show, edit, update e destroy.
 *
 * Cada matrícula está associada a um curso através da chave estrangeira course_id.
 */
class enrollmentController extends Controller
{
    /**
     * Lista todas as matrículas com o curso relacionado carregado.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        // with('course') carrega antecipadamente o relacionamento com o curso,
        // evitando consultas N+1 ao aceder a $enrollment->course.
        return Enrollment::with('course')->get();
    }

    /**
     * Exibe o formulário para criar uma nova matrícula (stub).
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
     * Armazena uma nova matrícula na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os dados da matrícula
     * @return \App\Models\Enrollment
     */
    public function store(Request $request)
    {
        // Valida os dados da matrícula:
        // name, email, phone, number_of_identify, shift e course_id são obrigatórios;
        // image é opcional; status deve ser um dos valores permitidos
        // (Pendente, Pago, Matriculado, Lista de Espera, Cancelado);
        // course_id deve referenciar um curso existente na tabela courses.
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'number_of_identify' => 'required|string',
            'image' => 'nullable|string',
            'shift' => 'required|string',
            'status' => 'sometimes|string|in:Pendente,Pago,Matriculado,Lista de Espera,Cancelado',
            'date' => 'nullable|date',
            'course_id' => 'required|exists:courses,id',
        ]);

        return Enrollment::create($validated);
    }

    /**
     * Exibe os detalhes de uma matrícula específica com o curso carregado.
     *
     * O modelo é injetado automaticamente pelo route model binding do Laravel.
     *
     * @param  \App\Models\Enrollment  $enrollment Matrícula a ser exibida
     * @return \App\Models\Enrollment
     */
    public function show(Enrollment $enrollment)
    {
        // Carrega o relacionamento course anexado ao modelo antes de devolvê-lo.
        return $enrollment->load('course');
    }

    /**
     * Exibe o formulário para editar uma matrícula existente (stub).
     *
     * @param  \App\Models\Enrollment  $enrollment Matrícula a ser editada
     * @return \App\Models\Enrollment
     */
    public function edit(Enrollment $enrollment)
    {
        // Retorna o recurso para edição (stub — sem view de formulário neste projeto).
        return $enrollment;
    }

    /**
     * Atualiza os dados de uma matrícula existente.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os novos dados
     * @param  \App\Models\Enrollment  $enrollment Matrícula a ser atualizada
     * @return \App\Models\Enrollment
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        // sometimes| aplica cada regra apenas quando o campo estiver presente,
        // permitindo atualizações parciais da matrícula.
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email',
            'phone' => 'sometimes|string',
            'number_of_identify' => 'sometimes|string',
            'image' => 'nullable|string',
            'shift' => 'sometimes|string',
            'status' => 'sometimes|string|in:Pendente,Pago,Matriculado,Lista de Espera,Cancelado',
            'date' => 'nullable|date',
            'course_id' => 'sometimes|exists:courses,id',
        ]);

        // Persiste as alterações apenas nos campos enviados na requisição.
        $enrollment->update($validated);

        return $enrollment;
    }

    /**
     * Remove uma matrícula da base de dados.
     *
     * Com o trait SoftDeletes no modelo, a remoção é lógica (coluna deleted_at).
     *
     * @param  \App\Models\Enrollment  $enrollment Matrícula a ser removida
     * @return \Illuminate\Http\Response
     */
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return response()->noContent();
    }
}
