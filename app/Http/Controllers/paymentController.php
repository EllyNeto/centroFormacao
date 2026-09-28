<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class paymentController extends Controller
{
   public function index()
    {
        // $payment = payment::all();
        return view('admin.payment.list.index');
    }


    public function create()
    {
        return view('admin.payment.create.index');
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
