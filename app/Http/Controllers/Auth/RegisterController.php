<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['index', 'createForm', 'store', 'destroy']);
        $this->middleware('auth')->only(['index', 'createForm', 'store', 'destroy']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();

        return view('admin.user.list.index', compact('users'));
    }

    public function createForm()
    {
        return view('admin.user.create.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required'      => 'O nome completo do utilizador é obrigatório.',
            'email.required'     => 'O endereço de e-mail é obrigatório.',
            'email.email'        => 'O endereço de e-mail não é válido.',
            'email.unique'       => 'Este endereço de e-mail já se encontra registado.',
            'password.required'  => 'A palavra-passe é obrigatória.',
            'password.min'       => 'A palavra-passe deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação da palavra-passe não coincide.',
        ]);

        $this->create($validated);

        return redirect()
            ->route('user.index')
            ->with('success', 'Utilizador registado com sucesso pelo administrador!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            return back()->with('error', 'Não pode eliminar a sua própria conta com sessão ativa.');
        }

        $user->delete();

        return redirect()
            ->route('user.index')
            ->with('success', 'Utilizador removido com sucesso!');
    }
}
