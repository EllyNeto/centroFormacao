<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

/**
 * Controlador classroomController - Gestão de Salas de Aula Físicas.
 * Responsável por todas as operações CRUD, validações diretas e regras de negócio das Salas.
 */
class classroomController extends Controller
{
    /**
     * Exibe a listagem de todas as salas cadastradas no sistema.
     * Ordena pelo número da sala e conta o número de turmas associadas a cada uma.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Consulta todas as salas ordenadas pelo número, trazendo a contagem da relação 'rooms'
        $classrooms = Classroom::withCount('rooms')
            ->orderBy('number_of_classroom', 'asc')
            ->get();

        // Retorna a vista de listagem com as salas obtidas
        return view('admin.classroom.list.index', compact('classrooms'));
    }

    /**
     * Exibe o formulário de cadastro de uma nova sala de aula.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retorna a vista com o formulário de criação de sala
        return view('admin.classroom.create.index');
    }

    /**
     * Valida e armazena uma nova sala de aula na base de dados.
     * Realiza a validação diretamente dentro do controlador.
     *
     * @param  \Illuminate\Http\Request  $request Dados recebidos do formulário
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // VALIDAÇÃO DIRETA NO CONTROLADOR:
        // Garante que o número da sala é único (ignorando eliminação lógica) e a capacidade é válida.
        $validatedData = $request->validate([
            'number_of_classroom' => 'required|integer|min:1|unique:classrooms,number_of_classroom,NULL,id,deleted_at,NULL',
            'capacity'            => 'required|integer|min:1',
            'description'         => 'nullable|string',
        ], [
            'number_of_classroom.required' => 'O número da sala é de preenchimento obrigatório.',
            'number_of_classroom.integer'  => 'O número da sala deve ser um valor inteiro válido.',
            'number_of_classroom.min'      => 'O número da sala deve ser pelo menos 1.',
            'number_of_classroom.unique'   => 'Este número de sala já se encontra registado no sistema.',
            'capacity.required'            => 'A capacidade física da sala é de preenchimento obrigatório.',
            'capacity.integer'             => 'A capacidade deve ser um número inteiro.',
            'capacity.min'                 => 'A capacidade da sala deve ser de pelo menos 1 formando.',
        ]);

        // Cria a nova sala com os dados validados
        Classroom::create($validatedData);

        // Redireciona para a listagem de salas com mensagem de sucesso
        return redirect()
            ->route('classroom.index')
            ->with('success', 'Sala de aula criada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma sala de aula específica.
     * Apresenta os dados da sala e a lista de turmas alocadas com os respetivos formadores e cursos.
     *
     * @param  int  $id Identificador primário da sala
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura a sala pelo ID, carregando os dados das turmas vinculadas e suas relações
        $classroom = Classroom::with(['rooms.teacher', 'rooms.course'])->findOrFail($id);

        // Retorna a vista de detalhes da sala
        return view('admin.classroom.detail.index', compact('classroom'));
    }

    /**
     * Exibe o formulário de edição de uma sala de aula existente.
     *
     * @param  int  $id Identificador primário da sala
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Procura a sala pelo ID ou falha com erro 404
        $classroom = Classroom::findOrFail($id);

        // Retorna a vista de edição da sala
        return view('admin.classroom.edit.index', compact('classroom'));
    }

    /**
     * Atualiza os dados de uma sala de aula na base de dados.
     * Realiza a validação diretamente no controlador, verifica limitações de redução de capacidade
     * e atualiza em cadeia a capacidade das turmas associadas.
     *
     * @param  \Illuminate\Http\Request  $request Dados recebidos do formulário
     * @param  int  $id Identificador primário da sala
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Procura a sala pelo ID
        $classroom = Classroom::findOrFail($id);

        // VALIDAÇÃO DIRETA NO CONTROLADOR:
        // Valida os dados ignorando a verificação de unicidade para a própria sala a editar
        $validatedData = $request->validate([
            'number_of_classroom' => "required|integer|min:1|unique:classrooms,number_of_classroom,{$id},id,deleted_at,NULL",
            'capacity'            => 'required|integer|min:1',
            'description'         => 'nullable|string',
        ], [
            'number_of_classroom.required' => 'O número da sala é de preenchimento obrigatório.',
            'number_of_classroom.integer'  => 'O número da sala deve ser um valor inteiro válido.',
            'number_of_classroom.min'      => 'O número da sala deve ser pelo menos 1.',
            'number_of_classroom.unique'   => 'Este número de sala já se encontra registado no sistema.',
            'capacity.required'            => 'A capacidade física da sala é de preenchimento obrigatório.',
            'capacity.integer'             => 'A capacidade deve ser um número inteiro.',
            'capacity.min'                 => 'A capacidade da sala deve ser de pelo menos 1 formando.',
        ]);

        $newCapacity = (int) $validatedData['capacity'];

        // REGRA DE NEGÓCIO 1: Validação de Redução de Capacidade
        // Se tentar reduzir a capacidade, verifica se alguma turma nesta sala possui mais inscritos ativos do que a nova capacidade
        if ($newCapacity < $classroom->capacity) {
            $maxEnrolled = $classroom->maxEnrolledInActiveRooms();
            if ($maxEnrolled > $newCapacity) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'capacity' => "Não é possível reduzir a capacidade para {$newCapacity}. Existe pelo menos uma turma nesta sala com {$maxEnrolled} formandos ativos."
                    ]);
            }
        }

        // Atualiza os dados da sala
        $classroom->update($validatedData);

        // REGRA DE NEGÓCIO 2: Sincronização em Cadeia
        // Atualiza a capacidade máxima (max_capacity) de todas as turmas vinculadas a esta sala
        $classroom->rooms()->update(['max_capacity' => $newCapacity]);

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()
            ->route('classroom.index')
            ->with('success', 'Sala de aula e capacidade das turmas atualizadas com sucesso!');
    }

    /**
     * Remove uma sala de aula da base de dados (Soft Delete).
     * Aplica a regra de proteção impedindo a eliminação de salas com turmas ativas vinculadas.
     *
     * @param  int  $id Identificador primário da sala
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Procura a sala pelo ID
        $classroom = Classroom::findOrFail($id);

        // REGRA DE NEGÓCIO 3: Impedir eliminação de sala com turmas em uso
        if ($classroom->hasActiveRooms()) {
            return back()->with('error', 'Impossível eliminar a sala: existem turmas ativas vinculadas a este espaço. Reatribua ou encerre as turmas primeiro.');
        }

        // Realiza a eliminação lógica
        $classroom->delete();

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()
            ->route('classroom.index')
            ->with('success', 'Sala de aula eliminada com sucesso!');
    }
}
