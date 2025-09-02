<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Document; // Importa el modelo Document

class StudentProfessionalPracticesController extends Controller
{
    public function uploadDocument(Request $request)
    {
        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'document_type' => 'required|string',
            'document_file' => 'required|file|mimes:pdf,docx|max:5120', // 5MB máximo
            'comments' => 'nullable|string|max:255',
            'hours_completed' => 'nullable|integer|min:0|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Obtener el archivo
        $file = $request->file('document_file');
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Guardar el archivo en el sistema de archivos
        // Asegúrate de que el disco 'local' apunte a storage/app
        $path = $file->storeAs('uploads/practicas/' . Auth::id(), $fileName, 'local'); // Guarda en una carpeta por estudiante

        // Preparar los datos del documento
        $documentData = [
            'student_id' => Auth::id(),
            'document_type' => $request->document_type,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'comments' => $request->comments,
            'status' => 'pending', // Estado inicial
        ];

        // Si es un certificado de horas, agregar las horas completadas
        if ($request->document_type === 'certificado_horas' && $request->has('hours_completed')) {
            $documentData['hours_completed'] = $request->hours_completed;
        }

        // Guardar la información del documento en la base de datos
        Document::create($documentData);

        return redirect()->back()->with('success', 'Documento subido exitosamente.');
    }

    // Nuevo método para mostrar los documentos
    public function index()
    {
        // Obtener el estudiante autenticado
        $student = Auth::guard('student')->user();
        
        // Obtener los documentos del estudiante autenticado con información del tutor
        $uploadedDocuments = $student->documents()
            ->with(['student.activeTutorAssignment.teacher', 'activeTutor'])
            ->latest()
            ->get();

        return view('auth.student.professional_practices.index', compact('uploadedDocuments', 'student'));
    }

    // Método para descargar documentos
    public function downloadDocument(Document $document)
    {
        // Asegúrate de que el estudiante autenticado sea el dueño del documento
        if (Auth::id() !== $document->student_id) {
            abort(403, 'Acceso no autorizado.');
        }

        return Storage::download($document->file_path, $document->file_name);
    }

    // Método para eliminar documentos
    public function destroy(Document $document)
    {
        // Asegúrate de que el estudiante autenticado sea el dueño del documento
        if (Auth::id() !== $document->student_id) {
            abort(403, 'Acceso no autorizado.');
        }

        // Verificar que el documento esté en estado pendiente o rechazado (solo se pueden eliminar documentos pendientes o rechazados)
        if (($document->teacher_status !== 'pending' && $document->teacher_status !== 'rejected') || 
            ($document->coordinator_status !== 'pending' && $document->coordinator_status !== 'rejected')) {
            return redirect()->back()->with('error', 'Solo se pueden eliminar documentos que estén en estado pendiente o rechazado.');
        }

        try {
            // Eliminar el archivo físico del sistema de archivos
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }

            // Eliminar el registro de la base de datos
            $document->delete();

            // Redirigir a la página de prácticas para que se recarguen los datos actualizados
            return redirect()->route('estudiante.practices.index')->with('success', 'Documento eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el documento. Por favor, inténtalo de nuevo.');
        }
    }

    // Método para solicitar asignación de tutor
    public function requestTutorAssignment()
    {
        $student = Auth::guard('student')->user();
        
        return view('auth.student.tutor_assignment.request', compact('student'));
    }
}
