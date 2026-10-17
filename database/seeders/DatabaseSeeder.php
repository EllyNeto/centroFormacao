<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Database seeder principal.
 * 
 * Conforme a estrutura ideal, este seeder chama:
 * - UserSeeder.php
 * - ClassroomSeeder.php  
 * - CourseSeeder.php
 * - TeacherSeeder.php
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ClassroomSeeder::class,
            CourseSeeder::class,
            TeacherSeeder::class,
        ]);
    }
}
