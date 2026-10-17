<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder para formadores.
 * 
 * Para desenvolvimento e testes iniciais.
 */
class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Formadores de exemplo para desenvolvimento
        $teachers = [
            [
                'name' => 'Maria Silva',
                'email' => 'maria.silva@centroformacao.com',
                'gender' => 'Feminino',
                'specialization' => 'Informática',
                'number_of_identify' => '123456789',
                'phone' => '923456789',
            ],
            [
                'name' => 'João Santos',
                'email' => 'joao.santos@centroformacao.com',
                'gender' => 'Masculino',
                'specialization' => 'Programação Web',
                'number_of_identify' => '987654321',
                'phone' => '923456788',
            ],
            [
                'name' => 'Ana Pereira',
                'email' => 'ana.pereira@centroformacao.com',
                'gender' => 'Feminino',
                'specialization' => 'Línguas',
                'number_of_identify' => '456789123',
                'phone' => '923456787',
            ],
            [
                'name' => 'Carlos Eduardo',
                'email' => 'carlos.eduardo@centroformacao.com',
                'gender' => 'Masculino',
                'specialization' => 'Gestão',
                'number_of_identify' => '789123456',
                'phone' => '923456786',
            ],
        ];

        foreach ($teachers as $teacher) {
            Teacher::firstOrCreate($teacher);
        }
    }
}
