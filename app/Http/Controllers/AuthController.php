<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Lead;

/**
 * Controlador responsável pela Autenticação (Login / Logout) e
 * Gestão Interna de Utilizadores pelo Administrador.
 */
class AuthController extends Controller
{
    /**
     * Exibe o formulário de início de sessão (Login).
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processa a tentativa de autenticação do utilizador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Informe o seu endereço de e-mail.',
            'email.email'       => 'Informe um endereço de e-mail válido.',
            'password.required' => 'Informe a sua palavra-passe.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'As credenciais introduzidas não correspondem aos nossos registos.',
            ]);
    }

    /**
     * Termina a sessão ativa do utilizador com segurança.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Sessão terminada com sucesso.');
    }

    /**
     * Lista todos os utilizadores registados no sistema (Acesso restrito a utilizadores autenticados).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();

        return view('admin.user.list.index', compact('users'));
    }

    /**
     * Exibe o formulário interno para o administrador registar um novo utilizador.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.user.create.index');
    }

    /**
     * Armazena um novo utilizador criado pelo administrador (sem alterar a sessão atual).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required'      => 'O nome completo do utilizador é obrigatório.',
            'email.required'     => 'O endereço de e-mail é obrigatório.',
            'email.email'        => 'O endereço de e-mail não é válido.',
            'email.unique'       => 'Este endereço de e-mail já se encontra registado.',
            'password.required'  => 'A palavra-passe é obrigatória.',
            'password.min'       => 'A palavra-passe deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação da palavra-passe não coincide.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('user.index')
            ->with('success', 'Utilizador registado com sucesso pelo administrador!');
    }

    /**
     * Remove um utilizador do sistema (impede auto-eliminação da própria conta em sessão).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            return back()->with('error', 'Não pode eliminar a sua própria conta com sessão ativa.');
        }

        $user->delete();

        return redirect()
            ->route('user.index')
            ->with('success', 'Utilizador removido com sucesso!');
    }

    public function showContactForm()
    {
        return view('auth.contact');
    }

    // ------------------------------
    // Store lead from login page
    // ------------------------------
    /**
     * Store a lead email submitted from the login page.
     */
    public function storeLead(Request $request)
    {
        $validated = $request->validate([
            'lead_email' => ['required', 'email', 'max:255', 'unique:leads,email'],
        ], [
            'lead_email.required' => 'O endereço de e‑mail é obrigatório.',
            'lead_email.email'    => 'Por favor, indique um e‑mail válido.',
            'lead_email.unique'   => 'Este e‑mail já está registado como lead.',
        ]);

        \App\Models\Lead::create([
            'email' => $validated['lead_email'],
        ]);

        return back()->with('status', 'Obrigado! Entraremos em contacto brevemente.');
    }

}