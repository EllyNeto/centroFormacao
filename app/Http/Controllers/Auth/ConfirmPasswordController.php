<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

/**
 * =========================================================================================
 * CONTROLADOR: Confirmação de Palavra-Passe (ConfirmPasswordController)
 * =========================================================================================
 * Solicita a re-confirmação da palavra-passe antes de permitir o acesso a áreas sensíveis do sistema.
 * Utiliza o Trait 'ConfirmsPasswords' do Laravel.
 */
class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords;

    /**
     * Rota de redirecionamento caso o acesso à URL pretendida falhe.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Construtor da classe.
     * Aplica o middleware de autenticação ('auth').
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
}

