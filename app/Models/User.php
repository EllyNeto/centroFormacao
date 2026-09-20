<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo Eloquent representando a entidade Utilizador / Administrador (User).
 * Responsável pela autenticação, perfis de acesso (super_admin vs admin)
 * e gestão de utilizadores da plataforma Centro de Formação.
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * Atributos permitidos para preenchimento em massa (Mass Assignment).
     *
     * @var array
     */
    protected $fillable = [
        'name',     // Nome completo do operador ou administrador
        'email',    // Endereço de e-mail de acesso/login
        'password', // Palavra-passe encriptada (Hash::make)
        'role',     // Perfil de acesso: 'super_admin' ou 'admin'
        'status',   // Estado da conta: true (Ativo) ou false (Desativado)
    ];

    /**
     * Atributos que devem ser ocultados ao serializar o modelo em arrays ou JSON.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Conversão de tipos de atributos (Casting).
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status'            => 'boolean',
    ];

    /**
     * Verifica se o utilizador possui o perfil de Super Administrador (Super Admin).
     * O Super Admin tem acesso total à plataforma, incluindo o módulo de Gestão de Utilizadores.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    /**
     * Verifica se o utilizador possui perfil de Administrador / Operador normal.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin' || $this->role === 'super_admin';
    }
}
