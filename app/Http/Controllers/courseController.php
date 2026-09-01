<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class courseController extends Controller
{
       public function index()
    {
        $course = Course::all();
        return view('course', ['course' => $course]);
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
