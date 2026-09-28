<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class teacherController extends Controller
{
        public function index()
    {
        // $teacher = Teacher::all();
        return view('admin.teacher.list.index');
    }


    public function create()
    {
        return view('admin.teacher.create.index');
    }


    public function store(Request $required)
    {
        return view();
    }


    public function edit($id)
    {
        return view();
    }


    public function update(Request $request)
    {
        return view();
    }


    public function show($id)
    {
        return view();
    }


    public function destroy($id)
    {
        return view();
    }
}
