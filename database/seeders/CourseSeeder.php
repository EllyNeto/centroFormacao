<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

/**
 * Seeder para cursos.
 * 
 * Para desenvolvimento e testes iniciais.
 */
class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cursos de exemplo para desenvolvimento
        $courses = [
            [
                'name' => 'Informática Básica',
                'description' => 'Curso introdutório aos conceitos de informática e uso de computadores',
                'duration' => 40,
                'status' => true,
                'value' => 15000.00,
            ],
            [
                'name' => 'Programação Web',
                'description' => 'Desenvolvimento de sites e aplicações web com HTML, CSS, JavaScript e PHP',
                'duration' => 80,
                'status' => true,
                'value' => 25000.00,
            ],
            [
                'name' => 'Inglês Intermediário',
                'description' => 'Curso de língua inglesa para nível intermediário',
                'duration' => 60,
                'status' => true,
                'value' => 18000.00,
            ],
            [
                'name' => 'Gestão de Projetos',
                'description' => 'Metodologias e ferramentas para gerenciamento eficaz de projetos',
                'duration' => 40,
                'status' => true,
                'value' => 22000.00,
            ],
        ];

        foreach ($courses as $course) {
            Course::firstOrCreate($course);
        }
    }
}
