<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

/**
 * Controlador responsável pela gestão das operações CRUD de Estudantes (Student).
 */
class studentController extends Controller
{
    /**
     * Exibe a listagem de todos os estudantes registados na base de dados.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todos os estudantes registados ordenados pelo ID decrescente
        $students = Student::orderBy('id', 'desc')->get();

        // Retorna a vista de listagem passando a coleção de estudantes
        return view('student.index.index', ['students' => $students]);
    }

    /**
     * Exibe o formulário de criação de um novo estudante.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retorna a vista com o formulário para registar um novo estudante
        return view('student.create.index');
    }

    /**
     * Valida os dados submetidos e guarda um novo estudante na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação rigorosa dos dados recebidos do formulário de criação
        $validatedData = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'identity_card_number' => 'required|string|max:255',
            'phone'                => 'required|string|max:20',
            'code'                 => 'required|integer',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'                 => 'O nome do estudante é obrigatório.',
            'email.required'                => 'O email é obrigatório.',
            'email.email'                   => 'Insira um endereço de e-mail válido.',
            'identity_card_number.required' => 'O número do bilhete de identidade é obrigatório.',
            'phone.required'                => 'O número de telefone é obrigatório.',
            'code.required'                 => 'O código do estudante é obrigatório.',
            'code.integer'                  => 'O código deve ser um número inteiro.',
            'image.image'                   => 'O ficheiro selecionado deve ser uma imagem.',
            'image.mimes'                   => 'A imagem deve estar no formato JPG, JPEG, PNG ou WEBP.',
            'image.max'                     => 'A imagem não pode ter um tamanho superior a 2MB.',
        ]);

        // Garantir compatibilidade entre os campos phone_number e phone no banco de dados
        $validatedData['phone_number'] = $validatedData['phone'];

        // Processamento do upload da fotografia do estudante, se enviada
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image');
            $extension = $requestImage->getClientOriginalExtension() ?: $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
            $requestImage->move(public_path('img/students'), $imageName);

            // Adiciona o nome do ficheiro aos dados validados para gravação
            $validatedData['image'] = $imageName;
        }

        // Criação do registo na base de dados com os dados validados
        Student::create($validatedData);

        // Redireciona para a listagem com mensagem de sucesso na sessão
        return redirect()->route('student.index')->with('success', 'Estudante registado com sucesso!');
    }

    /**
     * Exibe os detalhes de um estudante específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura o estudante pelo ID ou lança erro 404 se não for encontrado
        $student = Student::findOrFail($id);

        // Retorna a vista de detalhes do estudante
        return view('student.show.index', ['student' => $student]);
    }

    /**
     * Exibe o formulário de edição para um estudante existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Procura o estudante pelo ID para pré-preencher o formulário
        $student = Student::findOrFail($id);

        // Retorna a vista de edição passando os dados do estudante
        return view('student.edit.index', ['student' => $student]);
    }

    /**
     * Valida e atualiza os dados de um estudante existente na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Procura o estudante a ser atualizado pelo ID
        $student = Student::findOrFail($id);

        // Validação dos dados submetidos no formulário de edição
        $validatedData = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'identity_card_number' => 'required|string|max:255',
            'phone'                => 'required|string|max:20',
            'code'                 => 'required|integer',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'                 => 'O nome do estudante é obrigatório.',
            'email.required'                => 'O email é obrigatório.',
            'email.email'                   => 'Insira um endereço de e-mail válido.',
            'identity_card_number.required' => 'O número do bilhete de identidade é obrigatório.',
            'phone.required'                => 'O número de telefone é obrigatório.',
            'code.required'                 => 'O código do estudante é obrigatório.',
            'code.integer'                  => 'O código deve ser um número inteiro.',
            'image.image'                   => 'O ficheiro selecionado deve ser uma imagem.',
            'image.mimes'                   => 'A imagem deve estar no formato JPG, JPEG, PNG ou WEBP.',
            'image.max'                     => 'A imagem não pode ter um tamanho superior a 2MB.',
        ]);

        // Garantir compatibilidade entre os campos phone_number e phone
        $validatedData['phone_number'] = $validatedData['phone'];

        // Processamento do upload da nova fotografia, se fornecida
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image');
            $extension = $requestImage->getClientOriginalExtension() ?: $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
            $requestImage->move(public_path('img/students'), $imageName);

            // Substitui o nome da imagem antiga pelo novo nome
            $validatedData['image'] = $imageName;
        }

        // Atualização das propriedades do estudante na base de dados
        $student->update($validatedData);

        // Redireciona para a listagem com mensagem de sucesso na sessão
        return redirect()->route('student.index')->with('success', 'Estudante atualizado com sucesso!');
    }

    /**
     * Remove um estudante da base de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Procura o estudante pelo ID
        $student = Student::findOrFail($id);

        // Apaga o registo do estudante da base de dados
        $student->delete();

        // Redireciona para a listagem com mensagem de sucesso na sessão
        return redirect()->route('student.index')->with('success', 'Estudante eliminado com sucesso!');
    }

    /**
     * Método auxiliar de dashboard do estudante (mantido para compatibilidade).
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Retorna a vista do dashboard principal de estudantes
        return view('dashboard.index');
    }
}
