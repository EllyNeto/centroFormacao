<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class enrollmentController extends Controller
{
    public function index()
    {
        // $enrollment = enrollment::all();
        return view('admin.enrollment.list.index');
    }


    public function create()
    {
        return view('admin.enrollment.create.index');
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
