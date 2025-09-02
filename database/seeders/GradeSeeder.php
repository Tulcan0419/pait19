<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunos estudiantes, materias y profesores existentes
        $students = Student::take(5)->get();
        $subjects = Subject::take(3)->get();
        $teachers = Teacher::take(2)->get();

        if ($students->isEmpty() || $subjects->isEmpty() || $teachers->isEmpty()) {
            $this->command->info('No hay suficientes estudiantes, materias o profesores para crear calificaciones de prueba.');
            return;
        }

        $gradeTypes = ['exam', 'homework', 'project', 'participation', 'final'];
        $gradeTitles = [
            'Examen Parcial 1',
            'Tarea 1 - Introducción',
            'Proyecto Final',
            'Participación en Clase',
            'Examen Final',
            'Tarea 2 - Desarrollo',
            'Proyecto Intermedio',
            'Evaluación Continua'
        ];

        $comments = [
            'Excelente trabajo, muy bien desarrollado.',
            'Buen trabajo, pero puede mejorar.',
            'Necesita más esfuerzo y dedicación.',
            'Sobresaliente, felicitaciones.',
            'Cumple con los requisitos mínimos.',
            null,
            'Muy buena participación en clase.',
            'Proyecto bien estructurado y presentado.'
        ];

        foreach ($students as $student) {
            foreach ($subjects as $subject) {
                // Crear 3-5 calificaciones por estudiante por materia
                $numGrades = rand(3, 5);
                
                for ($i = 0; $i < $numGrades; $i++) {
                    $grade = rand(50, 100) / 10; // Calificaciones entre 5.0 y 10.0
                    $type = $gradeTypes[array_rand($gradeTypes)];
                    $title = $gradeTitles[array_rand($gradeTitles)] . ' - ' . ($i + 1); // Agregar número para evitar duplicados
                    $comment = $comments[array_rand($comments)];
                    $teacher = $teachers->random();
                    
                    // Fecha aleatoria en los últimos 3 meses
                    $evaluationDate = now()->subDays(rand(1, 90));

                    // Verificar si ya existe una calificación similar
                    $existingGrade = Grade::where('student_id', $student->id)
                        ->where('subject_id', $subject->id)
                        ->where('type', $type)
                        ->where('title', $title)
                        ->first();

                    if (!$existingGrade) {
                        Grade::create([
                            'student_id' => $student->id,
                            'subject_id' => $subject->id,
                            'teacher_id' => $teacher->id,
                            'grade' => $grade,
                            'type' => $type,
                            'title' => $title,
                            'comments' => $comment,
                            'evaluation_date' => $evaluationDate,
                        ]);
                    }
                }
            }
        }

        $this->command->info('Calificaciones de prueba creadas exitosamente.');
    }
}
