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
        $teachers = Teacher::orderBy('id', 'desc')->get();
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
     * Valida os dados submetidos e guarda um novo formador na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
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

        if (isset($validatedData['phone'])) {
            $validatedData['phone_number'] = $validatedData['phone'];
            unset($validatedData['phone']);
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image');
            $extension = $requestImage->getClientOriginalExtension() ?: $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
            $imagePath = $requestImage->storeAs('img/teacher', $imageName, 'public');

            $validatedData['image'] = $imagePath;
        }

        Teacher::create($validatedData);

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
        $teacher = Teacher::findOrFail($id);
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
        $teacher = Teacher::findOrFail($id);

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

        if (isset($validatedData['phone'])) {
            $validatedData['phone_number'] = $validatedData['phone'];
            unset($validatedData['phone']);
        }

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

        $teacher->update($validatedData);

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
        $teacher = Teacher::findOrFail($id);
        
        if ($teacher->image) {
            $caminhoCompleto = 'img/teacher/' . $teacher->image;
            if (Storage::exists($caminhoCompleto)) {
                Storage::disk('public')->delete($caminhoCompleto);
            }
        } 
        $teacher->delete();

        return redirect()->route('teacher.index')->with('success', 'Formador eliminado com sucesso!');
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
