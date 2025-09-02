<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\StudentTutorAssignment;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherProfessionalPracticesAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_without_assigned_students_cannot_access_professional_practices()
    {
        // Crear un profesor sin documentos asignados
        $teacher = Teacher::factory()->create();
        
        // Intentar acceder a las prácticas preprofesionales
        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('profesor.professional_practices.index'));
        
        // Debería ser redirigido al dashboard con un mensaje de error
        $response->assertRedirect(route('profesor.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_teacher_with_assigned_students_can_access_professional_practices()
    {
        // Crear un profesor
        $teacher = Teacher::factory()->create();
        
        // Crear un estudiante
        $student = Student::factory()->create();
        
        // Asignar el profesor como tutor del estudiante
        StudentTutorAssignment::create([
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'status' => 'active',
        ]);
        
        // Intentar acceder a las prácticas preprofesionales
        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('profesor.professional_practices.index'));
        
        // Debería poder acceder
        $response->assertStatus(200);
        $response->assertViewIs('auth.teacher.professional_practices.index');
    }

    public function test_teacher_cannot_modify_document_of_unassigned_student()
    {
        // Crear dos profesores
        $teacher1 = Teacher::factory()->create();
        $teacher2 = Teacher::factory()->create();
        
        // Crear dos estudiantes
        $student1 = Student::factory()->create();
        $student2 = Student::factory()->create();
        
        // Asignar el profesor 2 como tutor del estudiante 2
        StudentTutorAssignment::create([
            'student_id' => $student2->id,
            'teacher_id' => $teacher2->id,
            'status' => 'active',
        ]);
        
        // Crear un documento del estudiante 2
        $document = Document::factory()->create([
            'student_id' => $student2->id,
        ]);
        
        // El profesor 1 intenta modificar el documento del estudiante 2
        $response = $this->actingAs($teacher1, 'teacher')
            ->post(route('profesor.professional_practices.update_status', $document), [
                'status' => 'approved',
                'comments' => 'Test comment'
            ]);
        
        // Debería ser redirigido con un mensaje de error
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_teacher_cannot_download_document_of_unassigned_student()
    {
        // Crear dos profesores
        $teacher1 = Teacher::factory()->create();
        $teacher2 = Teacher::factory()->create();
        
        // Crear dos estudiantes
        $student1 = Student::factory()->create();
        $student2 = Student::factory()->create();
        
        // Asignar el profesor 2 como tutor del estudiante 2
        StudentTutorAssignment::create([
            'student_id' => $student2->id,
            'teacher_id' => $teacher2->id,
            'status' => 'active',
        ]);
        
        // Crear un documento del estudiante 2
        $document = Document::factory()->create([
            'student_id' => $student2->id,
        ]);
        
        // El profesor 1 intenta descargar el documento del estudiante 2
        $response = $this->actingAs($teacher1, 'teacher')
            ->get(route('profesor.professional_practices.download', $document));
        
        // Debería recibir un error 403
        $response->assertStatus(403);
    }
} 