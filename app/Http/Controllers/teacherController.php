<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        return view('admin.teacher.list.index', ['teachers' => $teachers]);
    }

    /**
     * Exibe o formulário de registo de um novo formador.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retorna a vista com o formulário para registar um novo formador
        return view('admin.teacher.create.index');
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
 
        // Upload da fotografia do formador se for enviada no formulário
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image');
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
             $imagePath = $requestImage->storeAs('storage', $imageName, 'public');

            // Substitui o nome da imagem antiga pelo novo nome
            $validatedData['image'] = $imagePath;
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
        return view('admin.teacher.show.index', ['teacher' => $teacher]);
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

        // 1. Verifica se o estudante tem uma imagem registada
        if ($teacher->image) {
            // 2. Reconstrói o caminho correto dentro de storage/app/public/
            $caminhoCompleto = 'storage/' . $teacher->image;
    
            // 3. Apaga o ficheiro do disco público se ele existir lá
            if (Storage::exists($caminhoCompleto)) {
                Storage::disk('local')->delete($caminhoCompleto);
            }
        }

        // Upload de nova imagem caso tenha sido selecionada
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image');
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
             $imagePath = $requestImage->storeAs('storage', $imageName, 'public');

            // Substitui o nome da imagem antiga pelo novo nome
            $validatedData['image'] = $imagePath;
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
        
        // 1. Verifica se o estudante tem uma imagem registada
        if ($teacher->image) {
            // 2. Reconstrói o caminho correto dentro de storage/app/public/
            $caminhoCompleto = 'storage/' . $teacher->image;
    
            // 3. Apaga o ficheiro do disco público se ele existir lá
            if (Storage::exists($caminhoCompleto)) {
                Storage::disk('local')->delete($caminhoCompleto);
            }
        } 
        // Elimina o registo do formador
        $teacher->delete();

        //falta apagar a foto

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
