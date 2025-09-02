<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Teacher;
use App\Models\SubjectTeacher;

class CleanTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:test-data {--type=all : Tipo de datos a limpiar (teachers, assignments, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia los datos de prueba del sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');

        if ($type === 'all' || $type === 'assignments') {
            $this->cleanAssignments();
        }

        if ($type === 'all' || $type === 'teachers') {
            $this->cleanTeachers();
        }

        $this->info('Limpieza de datos de prueba completada.');
    }

    /**
     * Limpia las asignaciones de prueba
     */
    private function cleanAssignments()
    {
        $this->info('Limpiando asignaciones de prueba...');
        
        $assignments = SubjectTeacher::where('comments', 'Asignación automática generada por seeder')->get();
        
        if ($assignments->count() > 0) {
            $deleted = SubjectTeacher::where('comments', 'Asignación automática generada por seeder')->delete();
            $this->info("Se eliminaron {$deleted} asignaciones de prueba.");
        } else {
            $this->info('No se encontraron asignaciones de prueba para eliminar.');
        }
    }

    /**
     * Limpia los profesores de prueba
     */
    private function cleanTeachers()
    {
        $this->info('Limpiando profesores de prueba...');
        
        $testEmails = [
            'carlos.mendoza@istpet.edu.ec',
            'maria.gonzalez@istpet.edu.ec',
            'roberto.silva@istpet.edu.ec',
            'ana.torres@istpet.edu.ec',
            'luis.ramirez@istpet.edu.ec',
            'carmen.vega@istpet.edu.ec'
        ];

        $teachers = Teacher::whereIn('email', $testEmails)->get();
        
        if ($teachers->count() > 0) {
            // Primero eliminar las asignaciones de estos profesores
            foreach ($teachers as $teacher) {
                SubjectTeacher::where('teacher_id', $teacher->id)->delete();
            }
            
            // Luego eliminar los profesores
            $deleted = Teacher::whereIn('email', $testEmails)->delete();
            $this->info("Se eliminaron {$deleted} profesores de prueba.");
        } else {
            $this->info('No se encontraron profesores de prueba para eliminar.');
        }
    }
}
