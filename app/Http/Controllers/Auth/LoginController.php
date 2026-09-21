<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * =========================================================================================
 * CONTROLADOR: Autenticação / Login de Utilizadores (LoginController)
 * =========================================================================================
 * Este controlador gere o processo de início e encerramento de sessão (login/logout) no sistema.
 * Utiliza o Trait 'AuthenticatesUsers' do Laravel para gerir validações de credenciais e redirecionamentos.
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Rota de destino/redirecionamento dos utilizadores após autenticação com sucesso.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Construtor do controlador.
     * Aplica o middleware 'guest' para impedir acesso de utilizadores já autenticados à tela de login,
     * excecionando a funcionalidade de 'logout'.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}

