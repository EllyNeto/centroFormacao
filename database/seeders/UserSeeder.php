<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * Seeder para usuários do sistema.
 * 
 * Cria o usuário administrador inicial conforme mencionado na estrutura ideal.
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador padrão se não existir
        if (!User::where('email', 'admin@centroformacao.com')->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@centroformacao.com',
                'password' => Hash::make('admin123'), // Em produção, usar senha forte e mudar no primeiro login
            ]);
        }
    }
}
