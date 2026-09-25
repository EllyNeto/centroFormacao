<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
class courseController extends Controller
{
    public function index()
    {
        // $course = Course::all();
        return view('admin.course.list.index');
    }


    public function create()
    {
        return view('course.create');
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
