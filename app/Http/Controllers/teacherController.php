<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

class teacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        return view('teachers', ['teachers' => $teachers]);
    }

    public function create()
    {
        return view('events.create');
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
    }

    public function dashboard()
    {
      
    }

    public function destroy($id)
    {
    }
}
