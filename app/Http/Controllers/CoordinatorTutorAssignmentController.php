<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Teacher;
use App\Models\Student;

class CoordinatorTutorAssignmentController extends Controller
{
    /**
     * Muestra la vista para asignar tutores a documentos de prácticas.
     */
    public function index(Request $request)
    {
        $students = Student::with('documents')->get();
        $teachers = Teacher::all();
        
        $selectedStudent = null;
        $documents = collect();

        if ($request->has('student_id') && $request->student_id) {
            $selectedStudent = Student::with('documents.tutor')->find($request->student_id);
            if ($selectedStudent) {
                $documents = $selectedStudent->documents()->with('tutor')->latest()->get();
            }
        } else {
            // Si no se selecciona estudiante, mostrar todos los documentos
            $documents = Document::with(['student', 'tutor'])->latest()->get();
        }

        return view('auth.coordinator.tutor_assignment.index', compact('students', 'teachers', 'selectedStudent', 'documents'));
    }

    /**
     * Asigna un tutor a un documento específico.
     */
    public function assignTutor(Request $request, Document $document)
    {
        $request->validate([
            'tutor_id' => 'required|exists:teachers,id',
        ]);

        $document->tutor_id = $request->tutor_id;
        $document->save();

        return redirect()->back()->with('success', 'Tutor asignado exitosamente.');
    }

    /**
     * Remueve el tutor asignado a un documento.
     */
    public function removeTutor(Document $document)
    {
        $document->tutor_id = null;
        $document->save();

        return redirect()->back()->with('success', 'Tutor removido exitosamente.');
    }

    /**
     * Muestra un reporte de asignaciones de tutores.
     */
    public function report()
    {
        $teachers = Teacher::withCount('tutoredDocuments')->get();
        $documentsWithoutTutor = Document::whereNull('tutor_id')->with('student')->get();
        $documentsWithTutor = Document::whereNotNull('tutor_id')->with(['student', 'tutor'])->get();

        return view('auth.coordinator.tutor_assignment.report', compact('teachers', 'documentsWithoutTutor', 'documentsWithTutor'));
    }
}
