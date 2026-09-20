<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

/**
 * =========================================================================================
 * CONTROLADOR: Envio de E-mail para Recuperação de Palavra-Passe (ForgotPasswordController)
 * =========================================================================================
 * Este controlador lida com o envio de e-mails com links/tokens para redefinição de palavra-passe esquecida.
 * Utiliza o Trait 'SendsPasswordResetEmails' do Laravel.
 */
class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;
}

