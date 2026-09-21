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
     * Verifica se o utilizador possui o perfil de Administrador / Administrador (Acesso Total).
     * O Administrador/Admin tem acesso a todas as definições e gestão de utilizadores.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin' || $this->role === 'admin';
    }

    /**
     * Verifica se o utilizador possui o perfil de Operador da Secretaria (Gestão de Inscrições e Formandos).
     *
     * @return bool
     */
    public function isSecretaria()
    {
        return $this->role === 'secretaria';
    }

    /**
     * Verifica se o utilizador possui o perfil de Operador das Finanças (Gestão de Pagamentos e Faturas).
     *
     * @return bool
     */
    public function isFinancas()
    {
        return $this->role === 'financas';
    }

    /**
     * Accessor para obter a designação legível em português do perfil do utilizador.
     *
     * @return string
     */
    public function getRoleNameAttribute()
    {
        switch ($this->role) {
            case 'super_admin':
            case 'admin':
                return 'Administrador (Admin)';
            case 'secretaria':
                return 'Operador - Secretaria';
            case 'financas':
                return 'Operador - Finanças';
            default:
                return ucfirst($this->role ?? 'Operador');
        }
    }
}
