<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CertificateController extends Controller
{
    /**
     * Generar certificado de prácticas profesionales para un estudiante
     */
    public function generateCertificate(Request $request, Student $student = null)
    {
        // Si no se proporciona un estudiante, usar el autenticado
        if (!$student) {
            $student = Auth::guard('student')->user();
        }

        // Verificar que el usuario autenticado sea el estudiante o un coordinador/admin
        if (Auth::guard('student')->check() && Auth::guard('student')->id() !== $student->id) {
            abort(403, 'No tienes permisos para generar este certificado.');
        }

        // Verificar que el estudiante tenga todos los documentos aprobados
        if (!$this->hasAllDocumentsApproved($student)) {
            return redirect()->back()->with('error', 'No se puede generar el certificado. Todos los documentos deben estar aprobados.');
        }

        // Verificar que el estudiante haya completado las horas requeridas
        if (!$this->hasCompletedRequiredHours($student)) {
            return redirect()->back()->with('error', 'No se puede generar el certificado. Debes completar todas las horas requeridas.');
        }

        try {
            // Generar el certificado
            $certificatePath = $this->createCertificatePDF($student);
            
            // Guardar el certificado en la base de datos
            $this->saveCertificateRecord($student, $certificatePath);
            
            return response()->download($certificatePath, $this->getCertificateFileName($student));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el certificado: ' . $e->getMessage());
        }
    }

    /**
     * Verificar si el estudiante tiene todos los documentos aprobados
     */
    private function hasAllDocumentsApproved(Student $student): bool
    {
        $requiredDocuments = [
            'convenio',
            'plan_trabajo', 
            'bitacora_asistencia',
            'control_avance',
            'informe_estudiante',
            'certificado_horas'
        ];

        foreach ($requiredDocuments as $documentType) {
            $document = $student->documents()
                ->where('document_type', $documentType)
                ->where('teacher_status', 'approved')
                ->where('coordinator_status', 'approved')
                ->latest()
                ->first();

            if (!$document) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verificar si el estudiante ha completado las horas requeridas
     */
    private function hasCompletedRequiredHours(Student $student): bool
    {
        $requiredHours = $student->semester == 3 ? 96 : ($student->semester >= 4 ? 146 : 0);
        
        $certificateDocument = $student->documents()
            ->where('document_type', 'certificado_horas')
            ->where('teacher_status', 'approved')
            ->where('coordinator_status', 'approved')
            ->latest()
            ->first();

        if (!$certificateDocument || !$certificateDocument->hours_completed) {
            return false;
        }

        return $certificateDocument->hours_completed >= $requiredHours;
    }

    /**
     * Crear el PDF del certificado
     */
    private function createCertificatePDF(Student $student): string
    {
        // Obtener información del estudiante y documentos
        $documents = $student->documents()
            ->where('teacher_status', 'approved')
            ->where('coordinator_status', 'approved')
            ->get();

        $certificateDocument = $documents->where('document_type', 'certificado_horas')->first();
        $hoursCompleted = $certificateDocument ? $certificateDocument->hours_completed : 0;
        $requiredHours = $student->semester == 3 ? 96 : ($student->semester >= 4 ? 146 : 0);
        
        // Obtener información del tutor
        $tutor = $documents->first()->tutor ?? null;

        // Preparar datos para el certificado
        $certificateData = [
            'student' => $student,
            'tutor' => $tutor,
            'hoursCompleted' => $hoursCompleted,
            'requiredHours' => $requiredHours,
            'practiceType' => $student->semester >= 4 ? 'Prácticas Profesionales' : 'Prácticas Preprofesionales',
            'semester' => $student->semester,
            'career' => $this->getCareerName($student->career),
            'currentDate' => Carbon::now()->format('d/m/Y'),
            'documents' => $documents
        ];

        // Generar el PDF
        $pdf = PDF::loadView('certificates.practice_certificate', $certificateData);
        
        // Configurar el PDF
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        // Guardar el PDF
        $fileName = $this->getCertificateFileName($student);
        $filePath = 'certificates/' . $student->id . '/' . $fileName;
        
        Storage::disk('local')->put($filePath, $pdf->output());
        
        return Storage::disk('local')->path($filePath);
    }

    /**
     * Guardar el registro del certificado en la base de datos
     */
    private function saveCertificateRecord(Student $student, string $filePath): void
    {
        $fileName = $this->getCertificateFileName($student);
        
        Document::create([
            'student_id' => $student->id,
            'document_type' => 'certificado_practicas',
            'file_name' => $fileName,
            'file_path' => 'certificates/' . $student->id . '/' . $fileName,
            'mime_type' => 'application/pdf',
            'file_size' => Storage::disk('local')->size('certificates/' . $student->id . '/' . $fileName),
            'comments' => 'Certificado de ' . ($student->semester >= 4 ? 'Prácticas Profesionales' : 'Prácticas Preprofesionales') . ' generado automáticamente',
            'status' => 'approved',
            'teacher_status' => 'approved',
            'coordinator_status' => 'approved',
            'hours_completed' => $student->semester == 3 ? 96 : ($student->semester >= 4 ? 146 : 0)
        ]);
    }

    /**
     * Obtener el nombre del archivo del certificado
     */
    private function getCertificateFileName(Student $student): string
    {
        $practiceType = $student->semester >= 4 ? 'Practicas_Profesionales' : 'Practicas_Preprofesionales';
        $currentDate = Carbon::now()->format('Y-m-d');
        
        return "Certificado_{$practiceType}_{$student->name}_{$currentDate}.pdf";
    }

    /**
     * Obtener el nombre de la carrera
     */
    private function getCareerName(string $career): string
    {
        $careers = [
            'mechanical' => 'Ingeniería Mecánica',
            'software' => 'Desarrollo de Software',
            'education' => 'Educación Básica'
        ];

        return $careers[$career] ?? ucfirst($career);
    }

    /**
     * Descargar certificado existente
     */
    public function downloadCertificate(Document $document)
    {
        // Verificar que sea un certificado
        if ($document->document_type !== 'certificado_practicas') {
            abort(404, 'Documento no encontrado.');
        }

        // Verificar permisos
        if (Auth::guard('student')->check() && Auth::guard('student')->id() !== $document->student_id) {
            abort(403, 'No tienes permisos para descargar este certificado.');
        }

        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('local')->download($document->file_path, $document->file_name);
    }

    /**
     * Verificar si el estudiante puede generar certificado
     */
    public function checkCertificateEligibility(Student $student = null)
    {
        if (!$student) {
            $student = Auth::guard('student')->user();
        }

        $hasAllDocumentsApproved = $this->hasAllDocumentsApproved($student);
        $hasCompletedRequiredHours = $this->hasCompletedRequiredHours($student);
        $hasCertificate = $student->documents()
            ->where('document_type', 'certificado_practicas')
            ->exists();

        return response()->json([
            'eligible' => $hasAllDocumentsApproved && $hasCompletedRequiredHours && !$hasCertificate,
            'hasAllDocumentsApproved' => $hasAllDocumentsApproved,
            'hasCompletedRequiredHours' => $hasCompletedRequiredHours,
            'hasCertificate' => $hasCertificate,
            'message' => $this->getEligibilityMessage($hasAllDocumentsApproved, $hasCompletedRequiredHours, $hasCertificate)
        ]);
    }

    /**
     * Obtener mensaje de elegibilidad
     */
    private function getEligibilityMessage(bool $hasAllDocumentsApproved, bool $hasCompletedRequiredHours, bool $hasCertificate): string
    {
        if ($hasCertificate) {
            return 'Ya tienes un certificado generado.';
        }

        if (!$hasAllDocumentsApproved) {
            return 'Todos los documentos deben estar aprobados para generar el certificado.';
        }

        if (!$hasCompletedRequiredHours) {
            return 'Debes completar todas las horas requeridas para generar el certificado.';
        }

        return 'Puedes generar tu certificado de prácticas.';
    }
}
