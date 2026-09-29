<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NovaNotificacao;

class teacherController extends Controller
{
    /**
     * Exibe a listagem de formadores registados.
     */
    public function index()
    {
        $teachers = Teacher::latest()->get();
        return view('admin.teacher.list.index', compact('teachers'));
    }

    /**
     * Exibe o formulário de registo de novo formador.
     */
    public function create()
    {
        return view('admin.teacher.create.index');
    }

    public function sendNotification($id)
    {
        $sender = Teacher::find($id);

        $sender->notify(new NovaNotificacao());
    }
    /**
     * Armazena um novo formador na base de dados com upload de fotografia.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'specialization'     => 'required|string|max:255',
            'number_of_identify' => 'required|string|max:255',
            'phone'              => 'required|string|max:50',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'gender'              => 'required|string|max:50',
        ], [
            'name.required'               => 'O campo nome é obrigatório.',
            'email.required'              => 'O campo email é obrigatório.',
            'email.email'                 => 'Introduza um endereço de email válido.',
            'specialization.required'     => 'O campo especialização é obrigatório.',
            'number_of_identify.required' => 'O número de identificação é obrigatório.',
            'phone.required'              => 'O número de telefone é obrigatório.',
            'image.image'                 => 'O ficheiro selecionado deve ser uma imagem válida.',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image');
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $validatedData['image'] = $requestImage->storeAs('teachers', $imageName, 'public');
        } else {
            $validatedData['image'] = null;
        }
        /**this->sendNotification($validatedData['id']); */
        Teacher::create($validatedData);

        return redirect()->route('teacher.index')->with('success', 'Formador registado com sucesso!');
    }

    /**
     * Exibe os detalhes completos de um formador específico.
     */
    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('admin.teacher.details.index', compact('teacher'));
    }

    /**
     * Exibe o formulário de edição dos dados do formador.
     */
    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('admin.teacher.edit.index', compact('teacher'));
    }

    /**
     * Atualiza os dados do formador na base de dados.
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $validatedData = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'specialization'     => 'required|string|max:255',
            'number_of_identify' => 'required|string|max:255',
            'phone'              => 'required|string|max:50',
            'gender'             => 'required|string|max:50',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ], [
            'name.required'               => 'O campo nome é obrigatório.',
            'email.required'              => 'O campo email é obrigatório.',
            'email.email'                 => 'Introduza um endereço de email válido.',
            'specialization.required'     => 'O campo especialização é obrigatório.',
            'number_of_identify.required' => 'O número de identificação é obrigatório.',
            'phone.required'              => 'O número de telefone é obrigatório.',
            'gender.required'             => 'O campo género é obrigatório.',
            'image.image'                 => 'O ficheiro selecionado deve ser uma imagem válida.',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($teacher->image && Storage::disk('public')->exists($teacher->image)) {
                Storage::disk('public')->delete($teacher->image);
            }
            $requestImage = $request->file('image');
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $validatedData['image'] = $requestImage->storeAs('teachers', $imageName, 'public');
        }
        // $validatedData['email']->notify(new NovaNotificacao());
        $teacher->update($validatedData);

        return redirect()->route('teacher.index')->with('success', 'Formador atualizado com sucesso!');
    }

    /**
     * Remove o registo do formador (Soft Delete).
     */
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('teacher.index')->with('success', 'Formador eliminado com sucesso!');
    }
}
