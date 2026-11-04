<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

/**
 * Controlador RESTful para gerir salas de aula (classrooms).
 *
 * Implementa os sete métodos de recurso padrão do Laravel:
 * index, create, store, show, edit, update e destroy.
 *
 * A ligação de modelos de rotas (route model binding) injeta automaticamente a
 * instância do modelo nos métodos show, edit, update e destroy, com base no ID
 * passado na URL.
 */
class classroomController extends Controller
{
    /**
     * Lista todas as salas de aula com a contagem de turmas associadas.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        // withCount('rooms') acrescenta o campo rooms_count com o número de turmas
        // de cada sala, evitando consultas N+1 (uma consulta por sala).
        return Classroom::withCount('rooms')->get();
    }

    /**
     * Exibe o formulário para criar uma nova sala de aula.
     *
     * Neste projeto é um stub (esboço): a criação é feita por API, pelo que não
     * devolve uma view Blade.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Armazena uma nova sala de aula na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os dados da sala
     * @return \App\Models\Classroom
     */
    public function store(Request $request)
    {
        // Valida os dados de entrada antes de criar o registo:
        // number_of_classroom é obrigatório, inteiro e único na tabela classrooms;
        // capacity é obrigatório e inteiro; description é opcional (string).
        $validated = $request->validate([
            'number_of_classroom' => 'required|integer|unique:classrooms',
            'capacity' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        // Cria o registo usando apenas os campos listados em $fillable do modelo,
        // protegendo contra atribuição em massa de campos não permitidos.
        return Classroom::create($validated);
    }

    /**
     * Exibe os detalhes de uma sala de aula, incluindo as suas turmas.
     *
     * O modelo é injetado automaticamente pelo route model binding do Laravel.
     *
     * @param  \App\Models\Classroom  $classroom Sala de aula a ser exibida
     * @return \App\Models\Classroom
     */
    public function show(Classroom $classroom)
    {
        // Carrega o relacionamento rooms (turmas) anexado ao modelo antes de devolvê-lo.
        return $classroom->load('rooms');
    }

    /**
     * Exibe o formulário para editar uma sala de aula existente (stub).
     *
     * @param  \App\Models\Classroom  $classroom Sala de aula a ser editada
     * @return \App\Models\Classroom
     */
    public function edit(Classroom $classroom)
    {
        // Retorna o recurso para edição (stub — sem view de formulário neste projeto).
        return $classroom;
    }

    /**
     * Atualiza os dados de uma sala de aula existente.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os novos dados
     * @param  \App\Models\Classroom  $classroom Sala de aula a ser atualizada
     * @return \App\Models\Classroom
     */
    public function update(Request $request, Classroom $classroom)
    {
        // sometimes| aplica cada regra de validação apenas quando o campo estiver
        // presente na requisição, permitindo atualizações parciais.
        // unique:classrooms,number_of_classroom,{$classroom->id} ignora o próprio
        // registo na verificação de unicidade do número da sala.
        $validated = $request->validate([
            'number_of_classroom' => 'sometimes|integer|unique:classrooms,number_of_classroom,' . $classroom->id,
            'capacity' => 'sometimes|integer',
            'description' => 'nullable|string',
        ]);

        // Persiste as alterações apenas nos campos enviados na requisição.
        $classroom->update($validated);

        return $classroom;
    }

    /**
     * Remove uma sala de aula da base de dados.
     *
     * Com o trait SoftDeletes no modelo, a remoção é lógica (define a coluna
     * deleted_at) em vez de apagar o registo fisicamente.
     *
     * @param  \App\Models\Classroom  $classroom Sala de aula a ser removida
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();

        // Devolve a resposta HTTP 204 No Content (sem corpo de resposta).
        return response()->noContent();
    }
}
