<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;

/**
 * Controlador responsável pelas operações de Inscrição (Enrollment) e Registo de Candidatos.
 */
class enrollmentController extends Controller
{
    /**
     * Exibe a listagem de todas as inscrições registadas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $enrollments = Enrollment::with(['student', 'course'])->orderBy('id', 'desc')->get();
        return view('admin.enrollment.list.index', ['enrollments' => $enrollments]);
    }

    /**
     * Exibe o formulário de registo de nova inscrição de candidato.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $courses = Course::where('status', 1)->get();
        return view('admin.enrollment.create.index', [
            'courses' => $courses,
        ]);
    }

    /**
     * Valida os dados do candidato e da inscrição, garante BI único por candidato e regista a inscrição.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'identity_card_number' => 'required|string|max:255',
            'phone'                => 'required|string|max:20',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'course_id'            => 'required|exists:courses,id',
            'date'                 => 'required|date',
            'status'               => 'required|boolean',
        ], [
            'name.required'                 => 'O nome do candidato é obrigatório.',
            'email.required'                => 'O email é obrigatório.',
            'email.email'                   => 'Insira um endereço de e-mail válido.',
            'identity_card_number.required' => 'O número do bilhete de identidade é obrigatório.',
            'phone.required'                => 'O número de telefone é obrigatório.',
            'image.image'                   => 'O ficheiro selecionado deve ser uma imagem.',
            'image.mimes'                   => 'A imagem deve estar no formato JPG, JPEG, PNG ou WEBP.',
            'image.max'                     => 'A imagem não pode ter um tamanho superior a 2MB.',
            'course_id.required'            => 'Por favor selecione o curso.',
            'course_id.exists'              => 'O curso selecionado é inválido.',
            'date.required'                 => 'A data da inscrição é obrigatória.',
            'status.required'               => 'Selecione o estado da inscrição.',
        ]);

        // Procura por candidato/estudante existente com o mesmo número de BI (Garantia de BI Único)
        $student = Student::where('identity_card_number', $validatedData['identity_card_number'])->first();

        if ($student) {
            // Verifica se o estudante já possui inscrição registada no mesmo curso
            $existingEnrollment = Enrollment::where('student_id', $student->id)
                ->where('course_id', $validatedData['course_id'])
                ->first();

            if ($existingEnrollment) {
                return back()->withInput()->withErrors([
                    'identity_card_number' => 'O candidato com o BI "' . $validatedData['identity_card_number'] . '" já se encontra inscrito neste curso.'
                ]);
            }
        } else {
            // Upload da fotografia de perfil do candidato se fornecida
            $imagePath = null;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $requestImage = $request->file('image');
                $extension = $requestImage->getClientOriginalExtension() ?: $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName() . time()) . '.' . $extension;
                $imagePath = $requestImage->storeAs('img/student', $imageName, 'public');
            }

            // Gera o código único sequencial do estudante
            $maxCode = Student::max('code');
            $studentCode = $maxCode ? ($maxCode + 1) : 1001;

            // Cria o registo do candidato na tabela de estudantes
            $student = Student::create([
                'name'                 => $validatedData['name'],
                'email'                => $validatedData['email'],
                'identity_card_number' => $validatedData['identity_card_number'],
                'phone'                => $validatedData['phone'],
                'code'                 => $studentCode,
                'image'                => $imagePath,
            ]);
        }

        // Cria a inscrição associada ao candidato e curso
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id'  => $validatedData['course_id'],
            'date'        => $validatedData['date'],
            'status'      => $validatedData['status'],
        ]);

        // Se o utilizador clicou em "Guardar e Ir para Pagamento", redireciona para o formulário de pagamento
        if ($request->input('action') === 'save_and_pay') {
            return redirect()->route('payment.create', ['enrollment_id' => $enrollment->id])
                ->with('success', 'Inscrição efetuada com sucesso! Proceda com o pagamento da inscrição.');
        }

        return redirect()->route('enrollment.index')->with('success', 'Inscrição de candidato efetuada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma inscrição específica.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $enrollment = Enrollment::with(['student', 'course'])->findOrFail($id);
        return view('admin.enrollment.details.index', ['enrollment' => $enrollment]);
    }

    /**
     * Exibe o formulário para editar uma inscrição existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $students = Student::all();
        $courses  = Course::where('status', 1)->get();

        return view('admin.enrollment.edit.index', [
            'enrollment' => $enrollment,
            'students'   => $students,
            'courses'    => $courses,
        ]);
    }

    /**
     * Valida e atualiza uma inscrição na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id'  => 'required|exists:courses,id',
            'status'      => 'required|boolean',
        ], [
            'student_id.required' => 'Por favor selecione ou pesquise o estudante.',
            'student_id.exists'   => 'O estudante selecionado é inválido.',
            'course_id.required'  => 'Por favor selecione ou pesquise o curso.',
            'course_id.exists'    => 'O curso selecionado é inválido.',
            'status.required'     => 'Selecione o estado da inscrição.',
        ]);

        // Impede alteração da data original de inscrição
        $validatedData['date'] = $enrollment->date;

        $enrollment->update($validatedData);

        return redirect()->route('enrollment.index')->with('success', 'Inscrição atualizada com sucesso!');
    }

    /**
     * Remove uma inscrição da base de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return redirect()->route('enrollment.index')->with('success', 'Inscrição eliminada com sucesso!');
    }
}
