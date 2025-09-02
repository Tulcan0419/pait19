@extends('layouts.coordinator-dashboard')

@section('title', 'Reporte de Asignación de Tutores por Estudiante - Coordinador')

@section('content')
@php
    $careerTitles = [
        'mechanical' => 'Mecánica Automotriz',
        'software' => 'Desarrollo de Software',
        'education' => 'Educación Básica',
    ];
@endphp
<div class="tutor-assignment-report-container">
    <!-- Header -->
    <div class="page-header">
        <h1><i class="fas fa-chart-bar"></i> Reporte de Asignación de Tutores por Estudiante</h1>
        <p class="page-description">Análisis completo de las asignaciones de tutores y estudiantes sin asignar.</p>
    </div>

    <!-- Estadísticas Generales -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $teachers->count() }}</h3>
                    <p>Total de Profesores</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $teachers->where('active_student_tutor_assignments_count', '>', 0)->count() }}</h3>
                    <p>Profesores con Estudiantes Asignados</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $studentsWithTutor->count() }}</h3>
                    <p>Estudiantes con Tutor</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $studentsWithoutTutor->count() }}</h3>
                    <p>Estudiantes sin Tutor</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estudiantes sin Tutor -->
    <div class="section">
        <div class="section-card">
            <h3><i class="fas fa-exclamation-triangle"></i> Estudiantes sin Tutor Asignado</h3>
            
            @if($studentsWithoutTutor->count() > 0)
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Carrera</th>
                                <th>Semestre</th>
                                <th>Documentos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentsWithoutTutor as $student)
                                <tr>
                                    <td>
                                        <div class="student-info">
                                            <strong>{{ $student->name }}</strong>
                                            <small>{{ $student->student_code }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="career-badge">{{ $careerTitles[$student->career] ?? ucfirst($student->career) }}</span>
                                    </td>
                                    <td>
                                        <span class="semester-badge">{{ $student->semester }}° Semestre</span>
                                    </td>
                                    <td>
                                        <span class="documents-count">{{ $student->documents->count() }} documentos</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('coordinador.student_tutor_assignment.index', ['student_id' => $student->id]) }}" class="btn-assign-tutor">
                                            <i class="fas fa-user-plus"></i> Asignar Tutor
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="no-data">
                    <i class="fas fa-check-circle"></i>
                    <p>¡Excelente! Todos los estudiantes tienen tutores asignados.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Estudiantes con Tutor -->
    <div class="section">
        <div class="section-card">
            <h3><i class="fas fa-user-tie"></i> Estudiantes con Tutor Asignado</h3>
            
            @if($studentsWithTutor->count() > 0)
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Tutor Asignado</th>
                                <th>Carrera</th>
                                <th>Semestre</th>
                                <th>Documentos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentsWithTutor as $student)
                                <tr>
                                    <td>
                                        <div class="student-info">
                                            <strong>{{ $student->name }}</strong>
                                            <small>{{ $student->student_code }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="tutor-info">
                                            <strong>{{ $student->activeTutorAssignment->teacher->name }}</strong>
                                            <small>{{ $student->activeTutorAssignment->teacher->teacher_code }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="career-badge">{{ $careerTitles[$student->career] ?? ucfirst($student->career) }}</span>
                                    </td>
                                    <td>
                                        <span class="semester-badge">{{ $student->semester }}° Semestre</span>
                                    </td>
                                    <td>
                                        <span class="documents-count">{{ $student->documents->count() }} documentos</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('coordinador.student_tutor_assignment.index', ['student_id' => $student->id]) }}" class="btn-view-assignment">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="no-data">
                    <i class="fas fa-inbox"></i>
                    <p>No hay estudiantes con tutores asignados.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Profesores y sus Cargas de Trabajo -->
    <div class="section">
        <div class="section-card">
            <h3><i class="fas fa-chart-pie"></i> Carga de Trabajo por Profesor</h3>
            
            @if($teachers->count() > 0)
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Profesor</th>
                                <th>Código</th>
                                <th>Estudiantes Asignados</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                                <tr>
                                    <td>
                                        <div class="teacher-info">
                                            <strong>{{ $teacher->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="teacher-code">{{ $teacher->teacher_code }}</span>
                                    </td>
                                    <td>
                                        <span class="students-count">{{ $teacher->active_student_tutor_assignments_count }} estudiantes</span>
                                    </td>
                                    <td>
                                        @if($teacher->active_student_tutor_assignments_count > 0)
                                            <span class="status-badge active">Activo</span>
                                        @else
                                            <span class="status-badge inactive">Sin asignaciones</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('coordinador.student_tutor_assignment.index') }}" class="btn-view-assignments">
                                            <i class="fas fa-eye"></i> Ver Asignaciones
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="no-data">
                    <i class="fas fa-inbox"></i>
                    <p>No hay profesores registrados en el sistema.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Enlaces de Navegación -->
    <div class="navigation-links">
        <a href="{{ route('coordinador.student_tutor_assignment.index') }}" class="btn btn-primary">
            <i class="fas fa-user-tie"></i> Gestionar Asignaciones
        </a>
        <a href="{{ route('coordinador.professional_practices.index') }}" class="btn btn-secondary">
            <i class="fas fa-briefcase"></i> Gestionar Prácticas
        </a>
    </div>
</div>

<style>
.tutor-assignment-report-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 30px;
    text-align: center;
}

.page-header h1 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.page-description {
    color: #7f8c8d;
    font-size: 1.1rem;
}

.stats-section {
    margin-bottom: 30px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-card:nth-child(1) .stat-icon {
    background: #3498db;
}

.stat-card:nth-child(2) .stat-icon {
    background: #27ae60;
}

.stat-card:nth-child(3) .stat-icon {
    background: #f39c12;
}

.stat-card:nth-child(4) .stat-icon {
    background: #e74c3c;
}

.stat-content h3 {
    font-size: 2rem;
    margin: 0;
    color: #2c3e50;
}

.stat-content p {
    margin: 0;
    color: #7f8c8d;
    font-size: 0.9rem;
}

.section {
    margin-bottom: 30px;
}

.section-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.section-card h3 {
    padding: 20px;
    margin: 0;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    color: #2c3e50;
}

.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.data-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #2c3e50;
}

.student-info strong {
    display: block;
    color: #2c3e50;
}

.student-info small {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.teacher-info strong {
    color: #2c3e50;
}

.teacher-code {
    background: #95a5a6;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
}

.career-badge {
    background: #3498db;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
}

.semester-badge {
    background: #e74c3c;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
}

.tutor-info strong {
    display: block;
    color: #27ae60;
}

.tutor-info small {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.documents-count {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.students-count {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
}

.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
}

.btn-assign-tutor,
.btn-view-assignment,
.btn-view-assignments {
    background: #3498db;
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.8rem;
    transition: background-color 0.3s ease;
}

.btn-assign-tutor:hover,
.btn-view-assignment:hover,
.btn-view-assignments:hover {
    background: #2980b9;
    color: white;
    text-decoration: none;
}

.no-data {
    text-align: center;
    padding: 40px;
    color: #7f8c8d;
}

.no-data i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

.navigation-links {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.btn-secondary {
    background-color: #95a5a6;
    color: white;
}

.btn-secondary:hover {
    background-color: #7f8c8d;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .data-table {
        font-size: 0.9rem;
    }
    
    .data-table th,
    .data-table td {
        padding: 10px;
    }
}
</style>
@endsection 