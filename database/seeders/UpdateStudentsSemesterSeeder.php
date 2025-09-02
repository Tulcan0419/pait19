<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class UpdateStudentsSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Actualizar todos los estudiantes existentes con semestre 1 por defecto
        Student::whereNull('semester')->orWhere('semester', 0)->update(['semester' => 1]);
        
        // También podemos asignar semestres aleatorios para demostración
        $students = Student::all();
        foreach ($students as $student) {
            if ($student->semester == 1) {
                // Asignar semestres aleatorios entre 1 y 4 para demostración
                $student->update(['semester' => rand(1, 4)]);
            }
        }
    }
}
