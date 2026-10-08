<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class enrollmentController extends Controller
{
    /**
     * Exibe a listagem de inscrições/candidaturas.
     */
    public function index()
    {
        $enrollments = Enrollment::with('course')->latest()->get();
        return view('admin.enrollment.list.index', compact('enrollments'));
    }

    /**
     * Exibe o formulário de registo de nova candidatura.
     */
    public function create()
    {
        $courses = Course::all();
        return view('admin.enrollment.create.index', compact('courses'));
    }

    /**
     * Valida e armazena uma nova candidatura na base de dados (mesma lógica do Formador).
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'phone'              => 'required|string|max:50',
            'number_of_identify' => 'required|string|max:100',
            'course_id'          => 'required|exists:courses,id',
            'shift'              => 'required|string|max:255',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ], [
            'name.required'               => 'O nome completo do candidato é obrigatório.',
            'email.required'              => 'O email do candidato é obrigatório.',
            'email.email'                 => 'Introduza um endereço de email válido.',
            'phone.required'              => 'O número de telefone é obrigatório.',
            'number_of_identify.required' => 'O número de identificação é obrigatório.',
            'course_id.required'          => 'Por favor, selecione o curso pretendido.',
            'shift.required'              => 'Por favor, selecione o turno pretendido.',
            'image.image'                 => 'O ficheiro selecionado deve ser uma imagem válida.',
        ]);

        // Upload de Imagem com a mesma lógica do Formador
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->file('image');
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $validatedData['image'] = $requestImage->storeAs('candidates', $imageName, 'public');
        } else {
            $validatedData['image'] = null;
        }

        $validatedData['status'] = 'Pendente';
        $validatedData['date']   = now();

        Enrollment::create($validatedData);

        return redirect()->route('enrollment.index')->with('success', 'Candidatura registada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma candidatura.
     */
    public function show($id)
    {
        $enrollment = Enrollment::with(['course', 'payments'])->findOrFail($id);
        return view('admin.enrollment.details.index', compact('enrollment'));
    }

    /**
     * Exibe o formulário de edição de uma candidatura.
     */
    public function edit($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $courses    = Course::all();
        return view('admin.enrollment.edit.index', compact('enrollment', 'courses'));
    }

    /**
     * Atualiza os dados de uma candidatura na base de dados.
     */
    public function update(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $validatedData = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'phone'              => 'required|string|max:50',
            'number_of_identify' => 'required|string|max:100',
            'course_id'          => 'required|exists:courses,id',
            'shift'              => 'required|string|max:255',
            'status'             => 'required|string|max:100',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ], [
            'name.required'               => 'O nome completo do candidato é obrigatório.',
            'email.required'              => 'O email do candidato é obrigatório.',
            'phone.required'              => 'O número de telefone é obrigatório.',
            'number_of_identify.required' => 'O número de identificação é obrigatório.',
            'course_id.required'          => 'Por favor, selecione o curso pretendido.',
            'shift.required'              => 'Por favor, selecione o turno pretendido.',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($enrollment->image && Storage::disk('public')->exists($enrollment->image)) {
                Storage::disk('public')->delete($enrollment->image);
            }
            $requestImage = $request->file('image');
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $validatedData['image'] = $requestImage->storeAs('candidates', $imageName, 'public');
        }

        $enrollment->update($validatedData);

        return redirect()->route('enrollment.index')->with('success', 'Candidatura atualizada com sucesso!');
    }

    /**
     * Elimina uma candidatura (Soft Delete).
     */
    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return redirect()->route('enrollment.index')->with('success', 'Candidatura eliminada com sucesso!');
    }
}
