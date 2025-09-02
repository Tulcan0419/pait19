@extends('layouts.coordinator-dashboard')

@section('title', 'Asignación de Tutores - Coordinador')

@section('content')
<div class="tutor-assignment-container">
    <!-- Header -->
    <div class="page-header">
        <h1><i class="fas fa-user-tie"></i> Asignación de Tutores de Prácticas Pre Profesionales</h1>
        <p class="page-description">Gestiona la asignación de profesores como tutores para los documentos de prácticas de los estudiantes.</p>
    </div>

    <!-- Alertas -->
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Filtro de Estudiantes -->
    <div class="filter-section">
        <div class="filter-card">
            <h3><i class="fas fa-filter"></i> Filtrar por Estudiante</h3>
            <form action="{{ route('coordinador.tutor_assignment.index') }}" method="GET">
                <div class="form-group">
                    <label for="student_id">Estudiante:</label>
                    <select class="form-control" id="student_id" name="student_id" onchange="this.form.submit()">
                        <option value="">Todos los estudiantes</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ $selectedStudent && $selectedStudent->id == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->student_code }}) - {{ $student->career }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Documentos -->
    <div class="documents-section">
        <div class="documents-card">
            <h3><i class="fas fa-file-alt"></i> Documentos de Prácticas Pre Profesionales</h3>
            
            @if($documents->count() > 0)
                <div class="documents-table-container">
                    <table class="documents-table">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Tipo de Documento</th>
                                <th>Archivo</th>
                                <th>Tutor Asignado</th>
                                <th>Estado Profesor</th>
                                <th>Estado Coordinador</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                <tr>
                                    <td>
                                        <div class="student-info">
                                            <strong>{{ $document->student->name }}</strong>
                                            <small>{{ $document->student->student_code }}</small>
                                            <small>{{ $document->student->career }}</small>
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
                                        @if($document->tutor)
                                            <div class="tutor-info">
                                                <strong>{{ $document->tutor->name }}</strong>
                                                <small>{{ $document->tutor->teacher_code }}</small>
                                            </div>
                                        @else
                                            <span class="no-tutor">Sin tutor asignado</span>
                                        @endif
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
                                        <div class="actions">
                                            @if($document->tutor)
                                                <!-- Formulario para cambiar tutor -->
                                                <form action="{{ route('coordinador.tutor_assignment.assign', $document->id) }}" method="POST" class="tutor-form">
                                                    @csrf
                                                    <select name="tutor_id" class="tutor-select" onchange="this.form.submit()">
                                                        <option value="">Cambiar tutor...</option>
                                                        @foreach($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}" {{ $document->tutor_id == $teacher->id ? 'selected' : '' }}>
                                                                {{ $teacher->name }} ({{ $teacher->teacher_code }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                                
                                                <!-- Botón para remover tutor -->
                                                <form action="{{ route('coordinador.tutor_assignment.remove', $document->id) }}" method="POST" class="remove-form">
                                                    @csrf
                                                    <button type="submit" class="btn-remove-tutor" title="Remover tutor">
                                                        <i class="fas fa-user-times"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Formulario para asignar tutor -->
                                                <form action="{{ route('coordinador.tutor_assignment.assign', $document->id) }}" method="POST" class="tutor-form">
                                                    @csrf
                                                    <select name="tutor_id" class="tutor-select" onchange="this.form.submit()">
                                                        <option value="">Asignar tutor...</option>
                                                        @foreach($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}">
                                                                {{ $teacher->name }} ({{ $teacher->teacher_code }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="no-documents">
                    <i class="fas fa-inbox"></i>
                    <p>No hay documentos de prácticas pre profesionales disponibles.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Enlaces de Navegación -->
    <div class="navigation-links">
        <a href="{{ route('coordinador.tutor_assignment.report') }}" class="btn btn-secondary">
            <i class="fas fa-chart-bar"></i> Ver Reporte de Asignaciones
        </a>
        <a href="{{ route('coordinador.professional_practices.index') }}" class="btn btn-primary">
            <i class="fas fa-briefcase"></i> Volver a Prácticas Pre Profesionales
        </a>
    </div>
</div>

<style>
.tutor-assignment-container {
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

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.filter-section {
    margin-bottom: 20px;
}

.filter-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.filter-card h3 {
    margin: 0 0 15px 0;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #555;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
}

.documents-section {
    margin-bottom: 20px;
}

.documents-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.documents-card h3 {
    margin: 0 0 20px 0;
    color: #333;
}

.documents-table-container {
    overflow-x: auto;
}

.documents-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.documents-table th,
.documents-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.documents-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #555;
}

.student-info strong {
    display: block;
    color: #333;
}

.student-info small {
    display: block;
    color: #666;
    font-size: 12px;
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

.no-tutor {
    color: #f44336;
    font-style: italic;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.teacher-status.pending {
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

.coordinator-status.pending {
    background-color: #fff3cd;
    color: #856404;
}

.coordinator-status.approved {
    background-color: #d4edda;
    color: #155724;
}

.coordinator-status.rejected {
    background-color: #f8d7da;
    color: #721c24;
}

.actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.tutor-form {
    flex: 1;
}

.tutor-select {
    width: 100%;
    padding: 6px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 12px;
}

.remove-form {
    margin: 0;
}

.btn-remove-tutor {
    background-color: #f44336;
    color: white;
    border: none;
    padding: 6px 8px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}

.btn-remove-tutor:hover {
    background-color: #d32f2f;
}

.no-documents {
    text-align: center;
    padding: 40px;
    color: #666;
}

.no-documents i {
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