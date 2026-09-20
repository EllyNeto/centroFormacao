<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\_Class;
use App\Models\Course;
use App\Models\Teacher;

/**
 * =========================================================================================
 * CONTROLADOR: Gestão de Turmas (classController)
 * =========================================================================================
 * Este controlador é responsável pela gestão completa das operações CRUD das turmas (_Class).
 * Associa turmas a cursos ativos e formadores, gere horários, dias de aula, turnos e capacidade.
 */
class classController extends Controller
{
    /**
     * Exibe a listagem dinâmica de todas as turmas registadas na base de dados.
     * Utiliza Eager Loading ('course', 'teacher') para otimizar o desempenho das pesquisas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todas as turmas ordenadas pelo ID mais recente com as suas relações preenchidas
        $classes = _Class::with(['course', 'teacher'])->orderBy('id', 'desc')->get();

        // Retorna a view de listagem de turmas na área administrativa
        return view('admin.room.list.index', ['classes' => $classes]);
    }

    /**
     * Exibe o formulário de criação de uma nova turma.
     * Carrega apenas os cursos e formadores ativos no sistema (status = 1).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Obtém cursos e formadores ativos para seleção nos menus suspensos
        $courses  = Course::where('status', 1)->get();
        $teachers = Teacher::where('status', 1)->get();

        // Gera um código único sequencial automático (ex: TURMA-2026-01, TURMA-2026-02...)
        $year = date('Y');
        $count = _Class::whereYear('created_at', $year)->count() + 1;
        do {
            $autoCode = 'TURMA-' . $year . '-' . sprintf('%02d', $count);
            $exists   = _Class::where('code', $autoCode)->exists();
            if ($exists) {
                $count++;
            }
        } while ($exists);

        // Retorna a vista de criação de turma
        return view('admin.room.create.index', [
            'courses'  => $courses,
            'teachers' => $teachers,
            'autoCode' => $autoCode,
        ]);
    }

    /**
     * Valida os dados submetidos pelo formulário, gera código único automático e guarda a nova turma.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação rigorosa dos campos enviados pelo formulário de turma
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'days_of_week' => 'required',
            'code'         => 'nullable|string|max:100',
            'shift'        => 'required|string|max:50',
            'capacity'     => 'required|integer|min:1',
            'status'       => 'required|boolean',
            'teacher_id'   => 'required|exists:teachers,id',
            'course_id'    => 'required|exists:courses,id',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i',
        ], [
            'name.required'       => 'O nome da turma é de preenchimento obrigatório.',
            'shift.required'      => 'Por favor escolha o turno da turma.',
            'capacity.required'   => 'A capacidade máxima da turma é obrigatória.',
            'status.required'     => 'Por favor selecione o estado da turma.',
            'days_of_week'        => 'Selecione pelo menos um dia da semana.',
            'teacher_id.required' => 'Por favor, selecione um formador responsável.',
            'teacher_id.exists'   => 'O formador selecionado não existe na base de dados.',
            'course_id.required'  => 'Por favor, selecione o curso associado.',
            'course_id.exists'    => 'O curso selecionado não existe.',
        ]);

        // Se o código da turma não for indicado ou já existir, gera um código sequencial único automático
        if (empty($validatedData['code']) || _Class::where('code', $validatedData['code'])->exists()) {
            $year = date('Y');
            $count = _Class::whereYear('created_at', $year)->count() + 1;
            do {
                $autoCode = 'TURMA-' . $year . '-' . sprintf('%02d', $count);
                $exists   = _Class::where('code', $autoCode)->exists();
                if ($exists) {
                    $count++;
                }
            } while ($exists);
            $validatedData['code'] = $autoCode;
        }

        // Cria o registo da turma na base de dados
        _Class::create($validatedData);

        // Redireciona para a lista de turmas com mensagem de confirmação
        return redirect()->route('class.index')->with('success', 'Turma registada com sucesso!');
    }

    /**
     * Exibe a página com os detalhes de uma turma específica pelo ID.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura a turma pelo ID carregando o curso e formador associados
        $class = _Class::with(['course', 'teacher'])->findOrFail($id);

        // Retorna a view de detalhes da turma
        return view('admin.room.details.index', ['class' => $class]);
    }

    /**
     * Exibe o formulário para editar os dados de uma turma existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Carrega a turma e a lista de cursos e formadores ativos
        $class    = _Class::findOrFail($id);
        $courses  = Course::where('status', 1)->get();
        $teachers = Teacher::where('status', 1)->get();

        // Retorna a vista de edição
        return view('admin.room.edit.index', [
            'class'    => $class,
            'courses'  => $courses,
            'teachers' => $teachers,
        ]);
    }

    /**
     * Valida e atualiza os dados de uma turma na base de dados.
     * Preserva o código original identificador da turma.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Localiza a turma a ser atualizada
        $class = _Class::findOrFail($id);

        // Validação dos dados alterados
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'days_of_week' => 'required',
            'code'         => 'nullable|string|max:100',
            'shift'        => 'required|string|max:50',
            'capacity'     => 'required|integer|min:1',
            'status'       => 'required|boolean',
            'teacher_id'   => 'required|exists:teachers,id',
            'course_id'    => 'required|exists:courses,id',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i',
        ], [
            'name.required'       => 'O nome da turma é obrigatório.',
            'shift.required'      => 'Por favor escolha o turno da turma.',
            'capacity.required'   => 'A capacidade da turma é obrigatória.',
            'status.required'     => 'Por favor selecione o estado da turma.',
            'days_of_week'        => 'Selecione pelo menos um dia da semana.',
            'teacher_id.required' => 'Por favor, selecione um formador responsável.',
            'teacher_id.exists'   => 'O formador selecionado não existe.',
            'course_id.required'  => 'Por favor, selecione um curso associado.',
            'course_id.exists'    => 'O curso selecionado não existe.',
        ]);

        // Preserva o código original da turma para manter a consistência do registo
        $validatedData['code'] = $class->code;

        // Atualiza a turma na base de dados
        $class->update($validatedData);

        // Redireciona para a lista com mensagem de sucesso
        return redirect()->route('class.index')->with('success', 'Turma atualizada com sucesso!');
    }

    /**
     * Remove uma turma da base de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Localiza e remove o registo da turma
        $class = _Class::findOrFail($id);
        $class->delete();

        // Redireciona com mensagem de confirmação
        return redirect()->route('class.index')->with('success', 'Turma eliminada com sucesso!');
    }
}

