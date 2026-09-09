<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
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
        $students = Student::orderBy('id', 'desc')->get();
        return view('admin.student.list.index', ['students' => $students]);
    }

    /**
     * Exibe o formulário de criação de um novo estudante.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.student.create.index');
    }

    /**
     * Valida os dados submetidos e guarda um novo estudante na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Se o código não for fornecido ou for string não numérico, gera automaticamente um código numérico único
        if (!$request->filled('code') || !is_numeric($request->code)) {
            $maxCode = Student::max('code');
            $request->merge(['code' => $maxCode ? ($maxCode + 1) : 1001]);
        }

        // Validação dos dados recebidos do formulário de criação
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

        // Processamento do upload da fotografia do estudante
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image');
            $extension = $requestImage->getClientOriginalExtension() ?: $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
            $imagePath = $requestImage->storeAs('img/student', $imageName, 'public');

            $validatedData['image'] = $imagePath;
        }

        // Criação do registo na base de dados
        Student::create($validatedData);

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
        $student = Student::findOrFail($id);
        return view('admin.student.details.index', ['student' => $student]);
    }

    /**
     * Exibe o formulário de edição para um estudante existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('admin.student.edit.index', ['student' => $student]);
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
        $student = Student::findOrFail($id);

        $validatedData = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'identity_card_number' => 'required|string|max:255',
            'phone'                => 'required|string|max:20',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'                 => 'O nome do estudante é obrigatório.',
            'email.required'                => 'O email é obrigatório.',
            'email.email'                   => 'Insira um endereço de e-mail válido.',
            'identity_card_number.required' => 'O número do bilhete de identidade é obrigatório.',
            'phone.required'                => 'O número de telefone é obrigatório.',
            'image.image'                   => 'O ficheiro selecionado deve ser uma imagem.',
            'image.mimes'                   => 'A imagem deve estar no formato JPG, JPEG, PNG ou WEBP.',
            'image.max'                     => 'A imagem não pode ter um tamanho superior a 2MB.',
        ]);

  
        // Garante que o código do estudante não seja modificado
        $validatedData['code'] = $student->code;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($student->image) {
                $caminhoCompleto = 'img/student/' . $student->image;
                if (Storage::exists($caminhoCompleto)) {
                    Storage::disk('public')->delete($caminhoCompleto);
                }
            }

            $requestImage = $request->file('image');
            $extension = $requestImage->getClientOriginalExtension() ?: $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
            $imagePath = $requestImage->storeAs('img/student', $imageName, 'public');

            $validatedData['image'] = $imagePath;
        }

        $student->update($validatedData);

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
        $student = Student::findOrFail($id);
    
        if ($student->image) {
            $caminhoCompleto = 'img/student/' . $student->image;
            if (Storage::exists($caminhoCompleto)) {
                Storage::disk('public')->delete($caminhoCompleto);
            }
        }

        $student->delete();
    
        return redirect()->route('student.index')->with('success', 'Estudante eliminado com sucesso!');
    }

    /**
     * Exibe o painel principal do sistema (Dashboard).
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('admin.dashboard.index');
    }
}
