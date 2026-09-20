<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * =========================================================================================
 * CONTROLADOR: Registo Autônomo de Utilizadores (RegisterController)
 * =========================================================================================
 * Este controlador lida com o registo público de novos utilizadores, incluindo validação de campos
 * e criação da conta com palavra-passe encriptada (Bcrypt Hash).
 */
class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Rota de destino/redirecionamento dos utilizadores após a conclusão do registo.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Construtor da classe.
     * Aplica o middleware 'guest' garantindo que apenas convidados não autenticados acedem à página de registo.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validador de dados para o formulário de registo público de utilizador.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Cria a nova instância de utilizador na base de dados com a palavra-passe encriptada.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}

