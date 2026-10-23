<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return redirect()->route('login')->with('status', 'Sessão terminada com sucesso.');
    }

    public function showContactForm()
    {
        return view('auth.contact');
    }

    public function storeLead(Request $request)
    {
        $validated = $request->validate([
            'lead_email' => ['required', 'email', 'max:255', 'unique:leads,email'],
        ], [
            'lead_email.required' => 'O endereço de e‑mail é obrigatório.',
            'lead_email.email'    => 'Por favor, indique um e‑mail válido.',
            'lead_email.unique'   => 'Este e‑mail já está registado como lead.',
        ]);

        Lead::create([
            'email' => $validated['lead_email'],
        ]);

        return back()->with('status', 'Obrigado! Entraremos em contacto brevemente.');
    }
}
