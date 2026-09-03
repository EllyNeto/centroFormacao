<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

/**
 * Controlador responsável pela gestão das operações CRUD de Formadores (Teacher).
 */
class teacherController extends Controller
{
    /**
     * Exibe a listagem de todos os formadores registados na base de dados.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todos os formadores registados ordenados pelo ID decrescente
        $teachers = Teacher::orderBy('id', 'desc')->get();

        // Retorna a vista de listagem passando a coleção de formadores
        return view('teacher.index.index', ['teachers' => $teachers]);
    }

    /**
     * Exibe o formulário de registo de um novo formador.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retorna a vista com o formulário para registar um novo formador
        return view('teacher.create.index');
    }

    /**
     * Valida os dados submetidos e guarda um novo formador na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação dos dados recebidos do formulário do formador
        $validatedData = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'identity_card_number' => 'nullable|string|max:255',
            'phone'                => 'nullable|string|max:20',
            'status'               => 'nullable|boolean',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'O nome do formador é obrigatório.',
            'email.required' => 'O endereço de e-mail é obrigatório.',
            'email.email'    => 'Insira um e-mail válido.',
            'image.image'    => 'O ficheiro de imagem selecionado não é válido.',
            'image.max'      => 'A imagem não pode exceder o tamanho de 2MB.',
        ]);

        // Garantir compatibilidade no campo phone_number
        if (isset($validatedData['phone'])) {
            $validatedData['phone_number'] = $validatedData['phone'];
        }

        // Upload da fotografia do formador se for enviada no formulário
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image');
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
            $requestImage->move(public_path('img/teachers'), $imageName);

            // Adiciona o nome do ficheiro aos dados validados
            $validatedData['image'] = $imageName;
        }

        // Regista o novo formador na base de dados
        Teacher::create($validatedData);

        // Redireciona para a listagem de formadores com mensagem de sucesso
        return redirect()->route('teacher.index')->with('success', 'Formador registado com sucesso!');
    }

    /**
     * Exibe os detalhes de um formador específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura o formador pelo ID ou dispara erro 404 se não encontrado
        $teacher = Teacher::findOrFail($id);

        // Retorna a vista de detalhes passando o objeto $teacher
        return view('teacher.show.index', ['teacher' => $teacher]);
    }

    /**
     * Exibe o formulário de edição para um formador existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Procura o formador pelo ID
        $teacher = Teacher::findOrFail($id);

        // Retorna a vista de edição pré-preenchida com os dados do formador
        return view('teacher.edit.index', ['teacher' => $teacher]);
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
        // Procura o formador pelo ID
        $teacher = Teacher::findOrFail($id);

        // Validação dos dados submetidos
        $validatedData = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'identity_card_number' => 'nullable|string|max:255',
            'phone'                => 'nullable|string|max:20',
            'specialty'            => 'nullable|string|max:255',
            'status'               => 'nullable|boolean',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'O nome do formador é obrigatório.',
            'email.required' => 'O endereço de e-mail é obrigatório.',
            'email.email'    => 'Insira um e-mail válido.',
            'image.image'    => 'O ficheiro de imagem selecionado não é válido.',
            'image.max'      => 'A imagem não pode exceder o tamanho de 2MB.',
        ]);

        // Garantir compatibilidade no campo phone_number
        if (isset($validatedData['phone'])) {
            $validatedData['phone_number'] = $validatedData['phone'];
        }

        // Upload de nova imagem caso tenha sido selecionada
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image');
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
            $requestImage->move(public_path('img/teachers'), $imageName);

            $validatedData['image'] = $imageName;
        }

        // Atualiza os dados na base de dados
        $teacher->update($validatedData);

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()->route('teacher.index')->with('success', 'Formador atualizado com sucesso!');
    }

    /**
     * Remove um formador da base de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Procura o formador pelo ID
        $teacher = Teacher::findOrFail($id);

        // Elimina o registo do formador
        $teacher->delete();

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()->route('teacher.index')->with('success', 'Formador eliminado com sucesso!');
    }

    /**
     * Método auxiliar de dashboard do formador (mantido para compatibilidade).
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('dashboard.index');
    }
}
