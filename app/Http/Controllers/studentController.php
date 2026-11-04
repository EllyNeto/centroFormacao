<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

/**
 * Controlador RESTful para gerir formandos (alunos) da instituição de ensino.
 *
 * Implementa os sete métodos de recurso padrão do Laravel:
 * index, create, store, show, edit, update e destroy.
 */
class studentController extends Controller
{
    /**
     * Lista todos os formandos cadastrados.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        // Devolve todos os registos da tabela students.
        return Student::all();
    }

    /**
     * Exibe o formulário para criar um novo formando (stub).
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
     * Armazena um novo formando na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os dados do formando
     * @return \App\Models\Student
     */
    public function store(Request $request)
    {
        // Valida os dados do formando:
        // name (string), email (email válido e único na tabela students),
        // number_of_identify (string e único: BI), phone (inteiro),
        // code (inteiro e único: número do formando) e image (string opcional).
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:students',
            'number_of_identify' => 'required|string|unique:students',
            'phone' => 'required|integer',
            'code' => 'required|integer|unique:students',
            'image' => 'nullable|string',
        ]);

        return Student::create($validated);
    }

    /**
     * Exibe os detalhes de um formando específico.
     *
     * O modelo é injetado automaticamente pelo route model binding do Laravel.
     *
     * @param  \App\Models\Student  $student Formando a ser exibido
     * @return \App\Models\Student
     */
    public function show(Student $student)
    {
        // Devolve o registo do formando encontrado pelo binding de rota.
        return $student;
    }

    /**
     * Exibe o formulário para editar um formando existente (stub).
     *
     * @param  \App\Models\Student  $student Formando a ser editado
     * @return \App\Models\Student
     */
    public function edit(Student $student)
    {
        // Retorna o recurso para edição (stub — sem view de formulário neste projeto).
        return $student;
    }

    /**
     * Atualiza os dados de um formando existente.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP com os novos dados
     * @param  \App\Models\Student  $student Formando a ser atualizado
     * @return \App\Models\Student
     */
    public function update(Request $request, Student $student)
    {
        // sometimes| aplica cada regra apenas quando o campo estiver presente.
        // unique:students,email,{$student->id} ignora o próprio registo na verificação
        // de unicidade do email (mesmo para number_of_identify e code).
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:students,email,' . $student->id,
            'number_of_identify' => 'sometimes|string|unique:students,number_of_identify,' . $student->id,
            'phone' => 'sometimes|integer',
            'code' => 'sometimes|integer|unique:students,code,' . $student->id,
            'image' => 'nullable|string',
        ]);

        // Persiste as alterações apenas nos campos enviados na requisição.
        $student->update($validated);

        return $student;
    }

    /**
     * Remove um formando da base de dados.
     *
     * Com o trait SoftDeletes no modelo, a remoção é lógica (coluna deleted_at).
     *
     * @param  \App\Models\Student  $student Formando a ser removido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return response()->noContent();
    }
}
