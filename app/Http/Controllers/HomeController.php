<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * =========================================================================================
 * CONTROLADOR: Página Inicial / Dashboard Padrão (HomeController)
 * =========================================================================================
 * Responsável pelo reencaminhamento dos utilizadores autenticados para a vista inicial do sistema.
 */
class HomeController extends Controller
{
    /**
     * Construtor da classe.
     * Aplica o middleware de autenticação ('auth') para garantir que apenas utilizadores com sessão ativa acedem à página.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe o painel de boas-vindas / dashboard inicial da aplicação.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}

