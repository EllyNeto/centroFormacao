<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador responsável pela gestão de Utilizadores e Administradores do sistema (User CRUD).
 * Funcionalidade exclusiva do perfil Super Administrador (super_admin).
 */
class userController extends Controller
{
    /**
     * Construtor da classe que garante a aplicação dos middlewares de autenticação e proteção Super Admin.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'super_admin']);
    }

    /**
     * Exibe a listagem de todos os utilizadores e administradores registados na plataforma.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todos os utilizadores ordenados pelo id mais recente
        $users = User::orderBy('id', 'desc')->get();

        // Retorna a vista com a coleção de utilizadores
        return view('admin.user.index', ['users' => $users]);
    }

    /**
     * Exibe o formulário de criação de um novo elemento/administrador no sistema.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.user.create.index');
    }

    /**
     * Valida e guarda um novo utilizador/administrador na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação dos dados do novo utilizador
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|string|in:super_admin,admin',
        ], [
            'name.required'     => 'O nome completo é obrigatório.',
            'email.required'    => 'O endereço de e-mail é obrigatório.',
            'email.unique'      => 'Este e-mail já está registado no sistema.',
            'password.required' => 'A palavra-passe é obrigatória.',
            'password.min'      => 'A palavra-passe deve ter no mínimo 6 carateres.',
            'password.confirmed'=> 'A confirmação de palavra-passe não coincide.',
            'role.required'     => 'Selecione a função/perfil do utilizador.',
        ]);

        // Criação do utilizador com a palavra-passe encriptada (Hash::make)
        User::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role'     => $validatedData['role'],
            'status'   => true, // Conta ativa por predefinição
        ]);

        // Redireciona para a lista com mensagem de sucesso
        return redirect()->route('user.index')->with('success', 'Novo utilizador/administrador registado com sucesso!');
    }

    /**
     * Exibe o formulário de edição de dados de um utilizador existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit.index', ['user' => $user]);
    }

    /**
     * Atualiza os dados de um utilizador existente na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'role'     => 'required|string|in:super_admin,admin',
            'status'   => 'required|boolean',
        ], [
            'name.required'  => 'O nome completo é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.unique'   => 'Este e-mail já pertence a outro utilizador.',
            'role.required'  => 'Selecione o perfil do utilizador.',
        ]);

        // Atualização dos campos principais
        $user->name   = $validatedData['name'];
        $user->email  = $validatedData['email'];
        $user->role   = $validatedData['role'];
        $user->status = $validatedData['status'];

        // Se uma nova palavra-passe foi fornecida, encripta e atualiza
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Dados do utilizador atualizados com sucesso!');
    }

    /**
     * Elimina um utilizador do sistema (com proteção contra auto-eliminação do Super Admin ligado).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Impede que o próprio utilizador logado elimine a sua própria conta
        if (Auth::id() == $id) {
            return redirect()->route('user.index')->with('error', 'Operação não permitida: Não pode eliminar a sua própria conta em sessão.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'Utilizador removido do sistema com sucesso!');
    }
}
