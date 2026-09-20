<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

/**
 * =========================================================================================
 * CONTROLADOR: Verificação de E-mail de Utilizadores (VerificationController)
 * =========================================================================================
 * Este controlador gere a verificação formal do endereço de e-mail dos novos utilizadores do sistema.
 * Utiliza o Trait 'VerifiesEmails' do Laravel.
 */
class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Rota de redirecionamento dos utilizadores após a verificação bem-sucedida do e-mail.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Construtor da classe.
     * Aplica os middlewares de autenticação, verificação de assinatura digital ('signed') e limites de taxa de requisições ('throttle').
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}

