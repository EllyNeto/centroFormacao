<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * Seeder responsável por popular a conta inicial do Administrador (Admin)
 * no sistema Centro de Formação.
 */
class UserSeeder extends Seeder
{
    /**
     * Executa a população da base de dados com a conta do Admin.
     *
     * @return void
     */
    public function run()
    {
        // 1. Conta do Administrador (Administrador - Acesso Total)
        User::updateOrCreate(
            ['email' => 'admin@centro.com'],
            [
                'name'     => 'Administrador Geral',
                'password' => Hash::make('password123'),
                'role'     => 'super_admin',
                'status'   => true,
            ]
        );

        // 2. Conta do Operador da Secretaria (Inscrições, Formandos, Turmas, Cursos)
        // User::updateOrCreate(
        //     ['email' => 'secretaria@centro.com'],
        //     [
        //         'name'     => 'Operador da Secretaria',
        //         'password' => Hash::make('password123'),
        //         'role'     => 'secretaria',
        //         'status'   => true,
        //     ]
        // );

        // 3. Conta do Operador das Finanças (Pagamentos, Faturas e Saldos)
        // User::updateOrCreate(
        //     ['email' => 'financas@centro.com'],
        //     [
        //         'name'     => 'Operador das Finanças',
        //         'password' => Hash::make('password123'),
        //         'role'     => 'financas',
        //         'status'   => true,
        //     ]
        // );
    }
}
