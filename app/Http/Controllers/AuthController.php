<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * Controlador responsável pela Autenticação de Utilizadores (Login, Registo e Logout).
 */
class AuthController extends Controller
{
    /**
     * Exibe a tela de login.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.main');
        }
        return view('auth.login');
    }

    /**
     * Processa a tentativa de login do utilizador.
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
            'email.required'    => 'O campo e-mail é obrigatório.',
            'email.email'       => 'Insira um endereço de e-mail válido.',
            'password.required' => 'O campo senha é obrigatório.',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard/main')->with('success', 'Bem-vindo de volta!');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não coincidem com os nossos registos.',
        ])->onlyInput('email');
    }

    /**
     * Exibe o formulário de registo de novos utilizadores.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.main');
        }
        return view('auth.register');
    }

    /**
     * Processa o registo de um novo utilizador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required'     => 'O nome completo é obrigatório.',
            'email.required'    => 'O e-mail é obrigatório.',
            'email.unique'      => 'Este e-mail já se encontra registado no sistema.',
            'password.required' => 'A palavra-passe é obrigatória.',
            'password.min'      => 'A palavra-passe deve ter pelo menos 6 caracteres.',
            'password.confirmed'=> 'A confirmação da palavra-passe não coincide.',
        ]);

        $user = User::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.main')->with('success', 'Conta criada com sucesso! Bem-vindo ao sistema.');
    }

    /**
     * Encerra a sessão do utilizador (Logout).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sessão encerrada com sucesso.');
    }
}
