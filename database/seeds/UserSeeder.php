<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * Seeder responsável por popular a conta inicial do Super Administrador (Super Admin)
 * no sistema Centro de Formação.
 */
class UserSeeder extends Seeder
{
    /**
     * Executa a população da base de dados com a conta do Super Admin.
     *
     * @return void
     */
    public function run()
    {
        // Cria ou atualiza a conta principal do Super Administrador
        User::updateOrCreate(
            ['email' => 'admin@centro.com'],
            [
                'name'     => 'Super Administrador',
                'password' => Hash::make('password123'),
                'role'     => 'super_admin',
                'status'   => true,
            ]
        );
    }
}
