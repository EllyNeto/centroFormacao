<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Student;

/**
 * =========================================================================================
 * CONTROLADOR: Gestão de Estudantes / Formandos (studentController)
 * =========================================================================================
 * Este controlador é responsável pelas operações de gestão de estudantes confirmados.
 * Apenas candidatos com inscrições pagas e confirmadas (`status = 1`) figuram nesta listagem.
 */
class studentController extends Controller
{
    /**
     * Exibe a listagem de todos os estudantes que possuem pelo menos uma inscrição paga/confirmada (status = 1).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Filtra apenas formandos que possuam inscrição com estado Pago / Confirmado (status = 1)
        $students = Student::whereHas('enrollments', function ($query) {
            $query->where('status', 1);
        })->orderBy('id', 'desc')->get();

        // Retorna a view da lista de estudantes
        return view('admin.student.list.index', ['students' => $students]);
    }

    /**
     * Exibe o formulário para registar diretamente um novo estudante.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.student.create.index');
    }

    /**
     * Valida os dados submetidos, gera código numérico único e guarda um novo estudante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Se o código do estudante não tiver sido preenchido, gera um código numérico sequencial único (ex: 1001, 1002...)
        if (!$request->filled('code') || !is_numeric($request->code)) {
            $maxCode = Student::max('code');
            $request->merge(['code' => $maxCode ? ($maxCode + 1) : 1001]);
        }

        // Validação rigorosa dos dados do estudante
        $validatedData = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'identity_card_number' => 'required|string|max:255|unique:students,identity_card_number',
            'gender'               => 'nullable|string|in:Masculino,Feminino,Outro',
            'phone'                => 'required|string|max:20',
            'code'                 => 'required|integer',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'                 => 'O nome do estudante é de preenchimento obrigatório.',
            'email.required'                => 'O e-mail é de preenchimento obrigatório.',
            'email.email'                   => 'Insira um endereço de e-mail válido.',
            'identity_card_number.required' => 'O número do bilhete de identidade é obrigatório.',
            'identity_card_number.unique'   => 'Este número de BI já se encontra registado por outro estudante.',
            'phone.required'                => 'O número de telefone é obrigatório.',
            'code.required'                 => 'O código do estudante é obrigatório.',
            'code.integer'                  => 'O código deve ser um número inteiro.',
            'image.image'                   => 'O ficheiro selecionado deve ser uma imagem válida.',
            'image.mimes'                   => 'A imagem deve estar no formato JPG, JPEG, PNG ou WEBP.',
            'image.max'                     => 'A imagem não pode ter um tamanho superior a 2MB.',
        ]);

        // Processamento do upload da fotografia do estudante no diretório public/img/student
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image');
            $extension = $requestImage->getClientOriginalExtension() ?: $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
            $imagePath = $requestImage->storeAs('img/student', $imageName, 'public');

            $validatedData['image'] = $imagePath;
        }

        // Ajusta a chave 'phone' para a coluna 'phone_number' da tabela
        $validatedData['phone_number'] = $validatedData['phone'];
        unset($validatedData['phone']);

        // Regista o estudante na base de dados
        Student::create($validatedData);

        // Redireciona para a lista com mensagem de sucesso
        return redirect()->route('student.index')->with('success', 'Estudante registado com sucesso!');
    }

    /**
     * Exibe a página com os detalhes de um estudante específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Procura o estudante pelo ID ou lança erro 404
        $student = Student::findOrFail($id);
        
        // Retorna a view de detalhes do formando
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
        // Procura o estudante para pré-preencher o formulário
        $student = Student::findOrFail($id);
        return view('admin.student.edit.index', ['student' => $student]);
    }

    /**
     * Valida e atualiza os dados do estudante (incluindo fotografia) na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Localiza o estudante a ser editado
        $student = Student::findOrFail($id);

        // Validação dos dados do pedido de atualização
        $validatedData = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'identity_card_number' => 'required|string|max:255|unique:students,identity_card_number,' . $id,
            'gender'               => 'nullable|string|in:Masculino,Feminino,Outro',
            'phone'                => 'required|string|max:20',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'                 => 'O nome do estudante é obrigatório.',
            'email.required'                => 'O e-mail é obrigatório.',
            'email.email'                   => 'Insira um endereço de e-mail válido.',
            'identity_card_number.required' => 'O número do bilhete de identidade é obrigatório.',
            'identity_card_number.unique'   => 'Este número de BI já se encontra registado por outro estudante.',
            'phone.required'                => 'O número de telefone é obrigatório.',
            'image.image'                   => 'O ficheiro selecionado deve ser uma imagem.',
            'image.mimes'                   => 'A imagem deve estar no formato JPG, JPEG, PNG ou WEBP.',
            'image.max'                     => 'A imagem não pode ter um tamanho superior a 2MB.',
        ]);

        // Preserva o código numérico original do estudante
        $validatedData['code']         = $student->code;
        $validatedData['phone_number'] = $validatedData['phone'];
        unset($validatedData['phone']);

        // Substituição da imagem do perfil se enviada nova fotografia
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

        // Atualiza o estudante na base de dados
        $student->update($validatedData);

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()->route('student.index')->with('success', 'Estudante atualizado com sucesso!');
    }

    /**
     * Remove um estudante da base de dados e apaga a sua imagem de perfil.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Procura o estudante a eliminar
        $student = Student::findOrFail($id);
    
        // Elimina a foto de perfil do disco de armazenamento caso exista
        if ($student->image) {
            $caminhoCompleto = 'img/student/' . $student->image;
            if (Storage::exists($caminhoCompleto)) {
                Storage::disk('public')->delete($caminhoCompleto);
            }
        }

        // Remove o registo do estudante da base de dados
        $student->delete();
    
        // Redireciona para a lista com mensagem de confirmação
        return redirect()->route('student.index')->with('success', 'Estudante eliminado com sucesso!');
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

