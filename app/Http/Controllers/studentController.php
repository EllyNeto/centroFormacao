<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class studentController extends Controller
{
   public function index()
    {
        // $student = student::all();
        return view('admin.student.list.index');
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
