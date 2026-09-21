<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware de proteção responsável por verificar se o utilizador autenticado
 * possui permissões de Administrador (role = 'super_admin').
 * Impede o acesso não autorizado ao módulo de Gestão de Utilizadores.
 */
class CheckSuperAdmin
{
    /**
     * Manipula a requisição recebida e valida a função de Admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o utilizador está autenticado e se possui a função de super_admin
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return $next($request);
        }

        // Caso não possua permissão, redireciona para o dashboard com mensagem de erro explicativa
        return redirect()->route('dashboard.main')->with('error', 'Acesso negado: Apenas o Administrador pode aceder à Gestão de Utilizadores.');
    }
}
