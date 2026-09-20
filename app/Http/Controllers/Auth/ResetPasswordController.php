<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;

/**
 * =========================================================================================
 * CONTROLADOR: Redefinição de Palavra-Passe (ResetPasswordController)
 * =========================================================================================
 * Este controlador valida os tokens recebidos por e-mail e processa a alteração da palavra-passe do utilizador.
 * Utiliza o Trait 'ResetsPasswords' do Laravel.
 */
class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Rota de destino/redirecionamento após redefinição da palavra-passe com sucesso.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
}

