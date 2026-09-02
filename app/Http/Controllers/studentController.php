<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class studentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('student.index.index', ['students' => $students]);
    }

    public function create()
    {
        return view('student.create.index');
    }
    
    public function store(Request $request)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request)
    {
    }

    public function show($id)
    {
         return view('student.show.index');
    }

    public function dashboard()
    {
      
    }

    public function destroy($id)
    {
    }
}
