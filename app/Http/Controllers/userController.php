<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * =========================================================================================
 * CONTROLADOR: Gestão de Utilizadores e Contas do Sistema (userController)
 * =========================================================================================
 * Este controlador é responsável pela gestão completa das contas de utilizador e perfis de acesso
 * (`super_admin` / `admin` - Administradores, `secretaria` - Secretaria, `financas` - Finanças).
 * 
 * Proteção de Acesso:
 *  - Funcionalidade reservada em exclusivo aos Super Administradores via middleware ['auth', 'super_admin'].
 *  - Proteção contra auto-eliminação da conta do utilizador em sessão no método destroy().
 */
class userController extends Controller
{
    /**
     * Construtor da classe.
     * Aplica os middlewares de autenticação ('auth') e verificação de perfil Super Admin ('super_admin').
     */
    public function __construct()
    {
        $this->middleware(['auth', 'super_admin']);
    }

    /**
     * Exibe a listagem de todos os utilizadores e operadores registados na plataforma.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Procura todos os utilizadores ordenados do mais recente para o mais antigo
        $users = User::orderBy('id', 'desc')->get();

        // Retorna a vista com a coleção de utilizadores
        return view('admin.user.index', ['users' => $users]);
    }

    /**
     * Exibe o formulário para registar um novo utilizador ou operador no sistema.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.user.create.index');
    }

    /**
     * Valida os dados submetidos, encripta a palavra-passe e cria uma nova conta de utilizador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação estrita dos dados do novo utilizador (perfis permitidos: super_admin, admin, secretaria, financas)
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|string|in:super_admin,admin,secretaria,financas',
        ], [
            'name.required'     => 'O nome completo é de preenchimento obrigatório.',
            'email.required'    => 'O endereço de e-mail é de preenchimento obrigatório.',
            'email.unique'      => 'Este e-mail já se encontra registado por outro utilizador.',
            'password.required' => 'A palavra-passe é de preenchimento obrigatório.',
            'password.min'      => 'A palavra-passe deve conter pelo menos 6 carateres.',
            'password.confirmed'=> 'A confirmação de palavra-passe não coincide.',
            'role.required'     => 'Selecione a função/perfil de acesso do utilizador.',
        ]);

        // Criação da conta de utilizador com a palavra-passe encriptada (Bcrypt Hash)
        User::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role'     => $validatedData['role'],
            'status'   => true, // Conta ativa por predefinição
        ]);

        // Redireciona para a listagem com mensagem de confirmação
        return redirect()->route('user.index')->with('success', 'Novo utilizador registado com sucesso!');
    }

    /**
     * Exibe o formulário para editar os dados de um utilizador existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Localiza o utilizador pelo ID
        $user = User::findOrFail($id);

        // Retorna a vista de edição
        return view('admin.user.edit.index', ['user' => $user]);
    }

    /**
     * Valida e atualiza os dados, perfil de acesso e estado de um utilizador existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Localiza a conta a ser alterada
        $user = User::findOrFail($id);

        // Validação dos dados do formulário de edição
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'role'     => 'required|string|in:super_admin,admin,secretaria,financas',
            'status'   => 'required|boolean',
        ], [
            'name.required'  => 'O nome completo é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.unique'   => 'Este e-mail já pertence a outro utilizador.',
            'role.required'  => 'Selecione o perfil do utilizador.',
        ]);

        // Atualização das propriedades da conta
        $user->name   = $validatedData['name'];
        $user->email  = $validatedData['email'];
        $user->role   = $validatedData['role'];
        $user->status = $validatedData['status'];

        // Se uma nova palavra-passe tiver sido indicada, re-encripta e atualiza
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        // Guarda as alterações na base de dados
        $user->save();

        // Redireciona com mensagem de sucesso
        return redirect()->route('user.index')->with('success', 'Dados do utilizador atualizados com sucesso!');
    }

    /**
     * Elimina a conta de um utilizador da base de dados.
     * Contém validação para impedir que o utilizador autenticado apague a sua própria conta.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Impede a auto-eliminação da conta do utilizador atualmente autenticado
        if (Auth::id() == $id) {
            return redirect()->route('user.index')->with('error', 'Operação não permitida: Não pode eliminar a sua própria conta em sessão.');
        }

        // Procura e remove o utilizador selecionado
        $user = User::findOrFail($id);
        $user->delete();

        // Redireciona com mensagem de sucesso
        return redirect()->route('user.index')->with('success', 'Utilizador removido do sistema com sucesso!');
    }
}

