<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\SubjectTeacher;

class SubjectAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener profesores y materias
        $teachers = Teacher::all();
        $subjects = Subject::active()->get();
        
        if ($teachers->isEmpty() || $subjects->isEmpty()) {
            $this->command->warn('No hay profesores o materias disponibles para crear asignaciones.');
            return;
        }

        // Obtener el período académico actual del sistema
        $currentPeriod = $this->getCurrentAcademicPeriod();
        $this->command->info("Usando período académico actual: {$currentPeriod}");
        
        // Crear asignaciones de ejemplo
        $assignmentsCreated = 0;
        
        foreach ($teachers as $teacher) {
            // Asignar 2-4 materias por profesor
            $randomSubjects = $subjects->random(rand(2, 4));
            
            foreach ($randomSubjects as $subject) {
                // Verificar que no exista ya la asignación para el período actual
                $existingAssignment = SubjectTeacher::where('teacher_id', $teacher->id)
                    ->where('subject_id', $subject->id)
                    ->where('academic_period', $currentPeriod)
                    ->exists();
                
                if (!$existingAssignment) {
                    SubjectTeacher::create([
                        'teacher_id' => $teacher->id,
                        'subject_id' => $subject->id,
                        'academic_period' => $currentPeriod,
                        'status' => 'active',
                        'max_students' => rand(20, 40),
                        'comments' => 'Asignación automática generada por seeder',
                    ]);
                    
                    $assignmentsCreated++;
                }
            }
        }
        
        $this->command->info("Se crearon {$assignmentsCreated} asignaciones de materias a profesores para el período {$currentPeriod}.");
    }

    /**
     * Get current academic period.
     */
    private function getCurrentAcademicPeriod()
    {
        $year = date('Y');
        $month = date('n');
        
        // Si estamos en la primera mitad del año, es el segundo semestre del año anterior
        if ($month <= 6) {
            return ($year - 1) . '-' . $year;
        } else {
            return $year . '-' . ($year + 1);
        }
    }
}
