@extends('layouts.coordinator-dashboard')

@section('title', 'Reporte de Asignaciones de Tutores - Coordinador')

@section('content')
<div class="tutor-report-container">
    <!-- Header -->
    <div class="page-header">
        <h1><i class="fas fa-chart-bar"></i> Reporte de Asignaciones de Tutores</h1>
        <p class="page-description">Estadísticas y estado de las asignaciones de tutores para prácticas pre profesionales.</p>
    </div>

    <!-- Alertas -->
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Estadísticas Generales -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $teachers->count() }}</h3>
                    <p>Profesores Disponibles</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $teachers->where('tutored_documents_count', '>', 0)->count() }}</h3>
                    <p>Profesores con Asignaciones</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $documentsWithTutor->count() }}</h3>
                    <p>Documentos con Tutor</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $documentsWithoutTutor->count() }}</h3>
                    <p>Documentos Sin Tutor</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profesores y sus Asignaciones -->
    <div class="teachers-section">
        <div class="section-card">
            <h2><i class="fas fa-user-tie"></i> Profesores y sus Asignaciones</h2>
            
            @if($teachers->count() > 0)
                <div class="teachers-table-container">
                    <table class="teachers-table">
                        <thead>
                            <tr>
                                <th>Profesor</th>
                                <th>Código</th>
                                <th>Documentos Asignados</th>
                                <th>Estado de Asignaciones</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                                <tr>
                                    <td>
                                        <div class="teacher-info">
                                            <strong>{{ $teacher->name }}</strong>
                                            <small>{{ $teacher->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="teacher-code">{{ $teacher->teacher_code }}</span>
                                    </td>
                                    <td>
                                        <span class="assignment-count {{ $teacher->tutored_documents_count > 0 ? 'has-assignments' : 'no-assignments' }}">
                                            {{ $teacher->tutored_documents_count }} documento(s)
                                        </span>
                                    </td>
                                    <td>
                                        @if($teacher->tutored_documents_count > 0)
                                            <span class="status-active">Activo</span>
                                        @else
                                            <span class="status-inactive">Sin asignaciones</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('coordinador.tutor_assignment.index', ['teacher_id' => $teacher->id]) }}" class="btn-view-assignments">
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
                    <i class="fas fa-users"></i>
                    <p>No hay profesores registrados en el sistema.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Documentos Sin Tutor -->
    <div class="unassigned-section">
        <div class="section-card">
            <h2><i class="fas fa-exclamation-triangle"></i> Documentos Sin Tutor Asignado</h2>
            
            @if($documentsWithoutTutor->count() > 0)
                <div class="documents-table-container">
                    <table class="documents-table">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Tipo de Documento</th>
                                <th>Archivo</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentsWithoutTutor as $document)
                                <tr>
                                    <td>
                                        <div class="student-info">
                                            <strong>{{ $document->student->name }}</strong>
                                            <small>{{ $document->student->student_code }} - {{ $document->student->career }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="document-type">{{ $document->document_type }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="file-link">
                                            <i class="fas fa-file"></i> {{ $document->file_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="status-badge pending">Pendiente de Asignación</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('coordinador.tutor_assignment.index', ['student_id' => $document->student->id]) }}" class="btn-assign-tutor">
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
                    <p>¡Excelente! Todos los documentos tienen tutores asignados.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Documentos con Tutor -->
    <div class="assigned-section">
        <div class="section-card">
            <h2><i class="fas fa-check-circle"></i> Documentos con Tutor Asignado</h2>
            
            @if($documentsWithTutor->count() > 0)
                <div class="documents-table-container">
                    <table class="documents-table">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Tutor Asignado</th>
                                <th>Tipo de Documento</th>
                                <th>Estado Profesor</th>
                                <th>Estado Coordinador</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentsWithTutor as $document)
                                <tr>
                                    <td>
                                        <div class="student-info">
                                            <strong>{{ $document->student->name }}</strong>
                                            <small>{{ $document->student->student_code }} - {{ $document->student->career }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="tutor-info">
                                            <strong>{{ $document->tutor->name }}</strong>
                                            <small>{{ $document->tutor->teacher_code }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="document-type">{{ $document->document_type }}</span>
                                    </td>
                                    <td>
                                        <span class="status-badge teacher-status {{ $document->teacher_status }}">
                                            @switch($document->teacher_status)
                                                @case('pending')
                                                    Pendiente
                                                    @break
                                                @case('approved')
                                                    Aprobado
                                                    @break
                                                @case('rejected')
                                                    Rechazado
                                                    @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge coordinator-status {{ $document->coordinator_status }}">
                                            @switch($document->coordinator_status)
                                                @case('pending')
                                                    Pendiente
                                                    @break
                                                @case('approved')
                                                    Aprobado
                                                    @break
                                                @case('rejected')
                                                    Rechazado
                                                    @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('coordinador.tutor_assignment.index', ['student_id' => $document->student->id]) }}" class="btn-view-document">
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
                    <p>No hay documentos con tutores asignados.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Enlaces de Navegación -->
    <div class="navigation-links">
        <a href="{{ route('coordinador.tutor_assignment.index') }}" class="btn btn-primary">
            <i class="fas fa-user-tie"></i> Gestionar Asignaciones
        </a>
        <a href="{{ route('coordinador.professional_practices.index') }}" class="btn btn-secondary">
            <i class="fas fa-briefcase"></i> Volver a Prácticas Pre Profesionales
        </a>
    </div>
</div>

<style>
.tutor-report-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.page-header h1 {
    margin: 0;
    font-size: 24px;
}

.page-description {
    margin: 10px 0 0 0;
    opacity: 0.9;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
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
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-card:nth-child(1) .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-card:nth-child(2) .stat-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-card:nth-child(3) .stat-icon {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-card:nth-child(4) .stat-icon {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stat-content h3 {
    margin: 0;
    font-size: 28px;
    font-weight: bold;
    color: #333;
}

.stat-content p {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 14px;
}

.section-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.section-card h2 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 20px;
}

.teachers-table-container,
.documents-table-container {
    overflow-x: auto;
}

.teachers-table,
.documents-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.teachers-table th,
.teachers-table td,
.documents-table th,
.documents-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.teachers-table th,
.documents-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #555;
}

.teacher-info strong,
.student-info strong {
    display: block;
    color: #333;
}

.teacher-info small,
.student-info small {
    display: block;
    color: #666;
    font-size: 12px;
}

.teacher-code {
    background-color: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.assignment-count {
    font-weight: 600;
}

.assignment-count.has-assignments {
    color: #4caf50;
}

.assignment-count.no-assignments {
    color: #f44336;
}

.status-active {
    background-color: #d4edda;
    color: #155724;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-inactive {
    background-color: #f8d7da;
    color: #721c24;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.document-type {
    background-color: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.file-link {
    color: #2196f3;
    text-decoration: none;
}

.file-link:hover {
    text-decoration: underline;
}

.tutor-info strong {
    display: block;
    color: #333;
}

.tutor-info small {
    display: block;
    color: #666;
    font-size: 12px;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.pending {
    background-color: #fff3cd;
    color: #856404;
}

.teacher-status.approved {
    background-color: #d4edda;
    color: #155724;
}

.teacher-status.rejected {
    background-color: #f8d7da;
    color: #721c24;
}

.coordinator-status.approved {
    background-color: #d4edda;
    color: #155724;
}

.coordinator-status.rejected {
    background-color: #f8d7da;
    color: #721c24;
}

.btn-view-assignments,
.btn-assign-tutor,
.btn-view-document {
    background-color: #2196f3;
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 12px;
    transition: background-color 0.3s ease;
}

.btn-view-assignments:hover,
.btn-assign-tutor:hover,
.btn-view-document:hover {
    background-color: #1976d2;
    color: white;
    text-decoration: none;
}

.no-data {
    text-align: center;
    padding: 40px;
    color: #666;
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
    background-color: #2196f3;
    color: white;
}

.btn-primary:hover {
    background-color: #1976d2;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #545b62;
}
</style>
@endsection 