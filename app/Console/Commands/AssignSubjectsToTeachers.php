<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\SubjectTeacher;

class AssignSubjectsToTeachers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teachers:assign-subjects 
                            {--teacher= : ID del profesor específico}
                            {--subject= : ID de la materia específica}
                            {--period= : Período académico (opcional, usa el actual por defecto)}
                            {--max-students=30 : Máximo número de estudiantes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asignar materias a profesores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $teacherId = $this->option('teacher');
        $subjectId = $this->option('subject');
        $period = $this->option('period') ?: $this->getCurrentAcademicPeriod();
        $maxStudents = $this->option('max-students');

        $this->info("Período académico: {$period}");

        // Si no se especifican profesor o materia, mostrar opciones
        if (!$teacherId || !$subjectId) {
            $this->showAssignmentOptions($period);
            return;
        }

        // Asignar materia específica a profesor específico
        $this->assignSpecificSubject($teacherId, $subjectId, $period, $maxStudents);
    }

    /**
     * Mostrar opciones de asignación interactivas
     */
    private function showAssignmentOptions($period)
    {
        $teachers = Teacher::orderBy('name')->get();
        $subjects = Subject::active()->orderBy('name')->get();

        if ($teachers->isEmpty()) {
            $this->error('No hay profesores disponibles.');
            return;
        }

        if ($subjects->isEmpty()) {
            $this->error('No hay materias disponibles.');
            return;
        }

        $this->info("\nProfesores disponibles:");
        foreach ($teachers as $teacher) {
            $this->line("  {$teacher->id}. {$teacher->name} ({$teacher->email})");
        }

        $this->info("\nMaterias disponibles:");
        foreach ($subjects as $subject) {
            $this->line("  {$subject->id}. {$subject->name} (Semestre: {$subject->semester}, Carrera: {$subject->career})");
        }

        $teacherId = $this->ask("\nSelecciona el ID del profesor");
        $subjectId = $this->ask("Selecciona el ID de la materia");

        $teacher = Teacher::find($teacherId);
        $subject = Subject::find($subjectId);

        if (!$teacher || !$subject) {
            $this->error('Profesor o materia no encontrado.');
            return;
        }

        $this->assignSpecificSubject($teacherId, $subjectId, $period, 30);
    }

    /**
     * Asignar materia específica a profesor específico
     */
    private function assignSpecificSubject($teacherId, $subjectId, $period, $maxStudents)
    {
        $teacher = Teacher::find($teacherId);
        $subject = Subject::find($subjectId);

        if (!$teacher) {
            $this->error("Profesor con ID {$teacherId} no encontrado.");
            return;
        }

        if (!$subject) {
            $this->error("Materia con ID {$subjectId} no encontrada.");
            return;
        }

        // Verificar si ya existe la asignación
        $existingAssignment = SubjectTeacher::where('teacher_id', $teacherId)
            ->where('subject_id', $subjectId)
            ->where('academic_period', $period)
            ->first();

        if ($existingAssignment) {
            $this->warn("Ya existe una asignación para el profesor {$teacher->name} y la materia {$subject->name} en el período {$period}.");
            
            if ($this->confirm('¿Deseas actualizar la asignación existente?')) {
                $existingAssignment->update([
                    'status' => 'active',
                    'max_students' => $maxStudents,
                    'comments' => 'Actualizada por comando Artisan'
                ]);
                $this->info("Asignación actualizada exitosamente.");
            }
            return;
        }

        // Crear nueva asignación
        SubjectTeacher::create([
            'teacher_id' => $teacherId,
            'subject_id' => $subjectId,
            'academic_period' => $period,
            'status' => 'active',
            'max_students' => $maxStudents,
            'comments' => 'Creada por comando Artisan'
        ]);

        $this->info("Asignación creada exitosamente:");
        $this->line("  Profesor: {$teacher->name}");
        $this->line("  Materia: {$subject->name}");
        $this->line("  Período: {$period}");
        $this->line("  Máximo estudiantes: {$maxStudents}");
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