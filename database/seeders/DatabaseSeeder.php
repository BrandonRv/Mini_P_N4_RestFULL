<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $docenteSeeder = new DocenteSeeder();
        $docenteSeeder->run();
        $alumnoSeeder = new AlumnoSeeder();
        $alumnoSeeder->run();
        $cursoSeeder = new CursoSeeder();
        $cursoSeeder->run();
        $matriculaSeeder = new MatriculaSeeder();
        $matriculaSeeder->run();

    }
}
