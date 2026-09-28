<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class roomController extends Controller
{
    public function index()
    {
        // $room = Room::all();
        return view('admin.room.list.index');
    }


    public function create()
    {
        return view('admin.room.create.index');
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
