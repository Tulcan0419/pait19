<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Document;
use App\Models\Student;
use App\Notifications\DocumentStatusNotification;

class TestDocumentNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:document-notification {student_id} {status=approved} {reviewer=teacher}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test document notification system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $studentId = $this->argument('student_id');
        $status = $this->argument('status');
        $reviewer = $this->argument('reviewer');

        // Buscar el estudiante
        $student = Student::find($studentId);
        if (!$student) {
            $this->error("Estudiante con ID {$studentId} no encontrado.");
            return 1;
        }

        // Buscar un documento del estudiante
        $document = $student->documents()->first();
        if (!$document) {
            $this->error("El estudiante no tiene documentos.");
            return 1;
        }

        // Crear una notificación de prueba
        $notification = new DocumentStatusNotification(
            $document,
            $status,
            $reviewer,
            "Este es un comentario de prueba para el estado: {$status}"
        );

        // Enviar la notificación
        $student->notify($notification);

        $this->info("Notificación enviada exitosamente a {$student->name}");
        $this->info("Documento: {$document->document_type}");
        $this->info("Estado: {$status}");
        $this->info("Revisor: {$reviewer}");

        return 0;
    }
} 