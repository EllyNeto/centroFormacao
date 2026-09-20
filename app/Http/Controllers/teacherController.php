<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Teacher;

/**
 * =========================================================================================
 * CONTROLADOR: Gestão de Formadores / Professores (teacherController)
 * =========================================================================================
 * Este controlador é responsável pelas operações CRUD da entidade Formador (Teacher).
 * Permite registar formadores, atualizar dados de contacto, gerir o estado de atividade (status)
 * e realizar o upload/eliminação de fotografias de perfil.
 */
class teacherController extends Controller
{
    /**
     * Exibe a listagem de todos os formadores registados na base de dados.
     * Ordenados do registo mais recente para o mais antigo.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todos os formadores ordenados pelo ID decrescente
        $teachers = Teacher::orderBy('id', 'desc')->get();

        // Retorna a vista da lista de formadores
        return view('admin.teacher.list.index', ['teachers' => $teachers]);
    }

    /**
     * Exibe o formulário de registo de um novo formador.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $teachers = Teacher::all();
        return view('admin.teacher.create.index', ['teachers' => $teachers]);
    }

    /**
     * Valida os dados submetidos, processa o upload da imagem de perfil e guarda um novo formador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação dos dados do novo formador
        $validatedData = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'identity_card_number' => 'nullable|string|max:255',
            'gender'               => 'nullable|string|in:Masculino,Feminino,Outro',
            'phone'                => 'nullable|string|max:20',
            'status'               => 'nullable|boolean',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'  => 'O nome do formador é de preenchimento obrigatório.',
            'email.required' => 'O endereço de e-mail é de preenchimento obrigatório.',
            'email.email'    => 'Insira um e-mail válido.',
            'image.image'    => 'O ficheiro de imagem selecionado não é válido.',
            'image.max'      => 'A imagem não pode exceder o tamanho máximo de 2MB.',
        ]);

        // Mapeia a chave 'phone' enviada no formulário para 'phone_number'
        if (isset($validatedData['phone'])) {
            $validatedData['phone_number'] = $validatedData['phone'];
            unset($validatedData['phone']);
        }

        // Processamento do upload da fotografia do formador para o diretório public/img/teacher
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image');
            $extension = $requestImage->getClientOriginalExtension() ?: $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
            $imagePath = $requestImage->storeAs('img/teacher', $imageName, 'public');

            $validatedData['image'] = $imagePath;
        }

        // Regista o novo formador na base de dados
        Teacher::create($validatedData);

        // Redireciona para a lista de formadores com mensagem de sucesso
        return redirect()->route('teacher.index')->with('success', 'Formador registado com sucesso!');
    }

    /**
     * Exibe a página com os detalhes de um formador específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura o formador pelo ID ou lança erro 404
        $teacher = Teacher::findOrFail($id);

        // Retorna a view de detalhes do formador
        return view('admin.teacher.details.index', ['teacher' => $teacher]);
    }

    /**
     * Exibe o formulário de edição para um formador existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Procura o formador para pré-preenchimento do formulário
        $teacher = Teacher::findOrFail($id);
        return view('admin.teacher.edit.index', ['teacher' => $teacher]);
    }

    /**
     * Valida e atualiza os dados de um formador existente na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Localiza o formador a ser editado
        $teacher = Teacher::findOrFail($id);

        // Validação dos dados atualizados
        $validatedData = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'identity_card_number' => 'nullable|string|max:255',
            'gender'               => 'nullable|string|in:Masculino,Feminino,Outro',
            'phone'                => 'nullable|string|max:20',
            'status'               => 'nullable|boolean',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'  => 'O nome do formador é obrigatório.',
            'email.required' => 'O endereço de e-mail é obrigatório.',
            'email.email'    => 'Insira um e-mail válido.',
            'image.image'    => 'O ficheiro de imagem selecionado não é válido.',
            'image.max'      => 'A imagem não pode exceder o tamanho de 2MB.',
        ]);

        if (isset($validatedData['phone'])) {
            $validatedData['phone_number'] = $validatedData['phone'];
            unset($validatedData['phone']);
        }

        // Substituição da imagem de perfil caso um novo ficheiro seja enviado
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($teacher->image) {
                $caminhoCompleto = 'img/teacher/' . $teacher->image;
                if (Storage::exists($caminhoCompleto)) {
                    Storage::disk('public')->delete($caminhoCompleto);
                }
            }

            $requestImage = $request->file('image');
            $extension = $requestImage->getClientOriginalExtension() ?: $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
            $imagePath = $requestImage->storeAs('img/teacher', $imageName, 'public');

            $validatedData['image'] = $imagePath;
        }

        // Atualiza o formador na base de dados
        $teacher->update($validatedData);

        // Redireciona com mensagem de confirmação
        return redirect()->route('teacher.index')->with('success', 'Formador atualizado com sucesso!');
    }

    /**
     * Remove um formador da base de dados e elimina a sua imagem de perfil do armazenamento.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Localiza o formador a ser removido
        $teacher = Teacher::findOrFail($id);
        
        // Remove a imagem de perfil do disco de armazenamento caso exista
        if ($teacher->image) {
            $caminhoCompleto = 'img/teacher/' . $teacher->image;
            if (Storage::exists($caminhoCompleto)) {
                Storage::disk('public')->delete($caminhoCompleto);
            }
        } 
        
        // Elimina o registo da base de dados
        $teacher->delete();

        // Redireciona para a lista com mensagem de confirmação
        return redirect()->route('teacher.index')->with('success', 'Formador eliminado com sucesso!');
    }

    /**
     * Exibe o painel principal do sistema (Dashboard Administrativo).
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('admin.dashboard.index');
    }
}

