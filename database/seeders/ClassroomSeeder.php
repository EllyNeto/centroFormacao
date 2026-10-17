<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;

/**
 * Seeder para salas de aula.
 * 
 * Para desenvolvimento e testes iniciais.
 */
class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Salas de exemplo para desenvolvimento
        $classrooms = [
            ['number_of_classroom' => 101, 'capacity' => 30, 'description' => 'Sala com projetor e ar-condicionado'],
            ['number_of_classroom' => 102, 'capacity' => 25, 'description' => 'Sala padrão com quadro branco'],
            ['number_of_classroom' => 201, 'capacity' => 40, 'description' => 'Sala maior para cursos populares'],
            ['number_of_classroom' => 202, 'capacity' => 20, 'description' => 'Sala para cursos práticos'],
        ];

        foreach ($classrooms as $classroom) {
            Classroom::firstOrCreate($classroom);
        }
    }
}
