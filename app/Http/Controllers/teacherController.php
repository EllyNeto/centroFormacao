<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

class teacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        return view('teacher.index.index', ['teachers' => $teachers]);
    }

    public function create()
    {
        return view('teacher.create.index');
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
        return view('teacher.show.index');
    }

    public function dashboard()
    {
      
    }

    public function destroy($id)
    {
    }
}
