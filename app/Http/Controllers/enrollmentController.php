<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\_Class;

class enrollmentController extends Controller
{
    //
    public function index()
    {
        $enrollment = Enrollment::all();
        return view('admin.enrollment.list.index', ['enrollments' => $enrollment]);
    }

    public function create()
    {
        $student = Student::where([
                ['id', 'like', '%' .$search. '%' ]
            ])->get();
        return view('admin.enrollment.create.index');
    }

    public function store(Request $request)
    {
        return view('enrollment.index');
    }

    public function show($id)
    {
        // Procura dinamicamente a inscricao na base de dados pelo ID
        $enrollment = Enrollment::findOrFail($id);
        
        // Retorna a vista de detalhes passando o objeto da turma
        return view('admin.enrollment.details.index', ['enrollment' => $enrollment]);
    }
    
    public function update()
    {
        return view('enrollment.index');
    }
        
    public function destroy($id)
    {
        // Procura a turma pelo ID
        $enrollment = Enrollment::findOrFail($id);

        // Executa a eliminação suave (soft delete) da turma
        $enrollment->delete();

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()->route('enrollment.index')->with('success', 'Turma eliminada com sucesso!');
    }
}
