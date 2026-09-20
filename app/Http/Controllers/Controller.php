<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * =========================================================================================
 * CONTROLADOR BASE DA APLICAÇÃO (Controller)
 * =========================================================================================
 * Serve como classe ancestral para todos os controladores da aplicação Laravel.
 * Herda as funcionalidades fundamentais de autorização, despacho de jobs e validação de pedidos HTTP.
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

