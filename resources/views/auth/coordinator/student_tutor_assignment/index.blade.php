@extends('layouts.coordinator-dashboard')

@section('title', 'Asignación de Tutores por Estudiante - Coordinador')

@section('content')
<div class="student-tutor-assignment-container">
    <!-- Header -->
    <div class="page-header">
        <h1><i class="fas fa-user-tie"></i> Asignación de Tutores por Estudiante</h1>
        <p class="page-description">Gestiona la asignación de profesores como tutores para los estudiantes de prácticas pre profesionales.</p>
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

    <!-- Resumen por Carreras -->
    <div class="careers-summary">
        @php
            $careerTitles = [
                'mechanical' => 'Mecánica Automotriz',
                'software' => 'Desarrollo de Software',
                'education' => 'Educación Básica',
            ];
            
            $careersSummary = $students->groupBy('career')->map(function($studentsInCareer) use ($careerTitles) {
                return [
                    'total' => $studentsInCareer->count(),
                    'with_tutor' => $studentsInCareer->filter(function($student) {
                        return $student->activeTutorAssignment && $student->activeTutorAssignment->teacher;
                    })->count(),
                    'without_tutor' => $studentsInCareer->filter(function($student) {
                        return !$student->activeTutorAssignment || !$student->activeTutorAssignment->teacher;
                    })->count()
                ];
            })->sortKeys();
        @endphp
        
        <div class="summary-grid">
            @foreach($careersSummary as $career => $stats)
                <div class="summary-card">
                    <div class="summary-header">
                        <h4>{{ $careerTitles[$career] ?? ucfirst($career) }}</h4>
                        <span class="total-students">{{ $stats['total'] }} estudiantes</span>
                    </div>
                    <div class="summary-stats">
                        <div class="stat-item">
                            <span class="stat-label">Con tutor:</span>
                            <span class="stat-value with-tutor">{{ $stats['with_tutor'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Sin tutor:</span>
                            <span class="stat-value without-tutor">{{ $stats['without_tutor'] }}</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        @if($stats['total'] > 0)
                            <div class="progress-fill" style="width: {{ ($stats['with_tutor'] / $stats['total']) * 100 }}%"></div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Filtro de Estudiantes -->
    <div class="filter-section">
        <div class="filter-card">
            <h3><i class="fas fa-filter"></i> Filtrar Estudiantes</h3>
            <form action="{{ route('coordinador.student_tutor_assignment.index') }}" method="GET">
                <div class="form-group">
                    <label for="student_id">Seleccionar Estudiante:</label>
                    <select class="form-control" id="student_id" name="student_id" onchange="this.form.submit()">
                        <option value="">Ver todos los estudiantes</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ $selectedStudent && $selectedStudent->id == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->student_code }}) - {{ $careerTitles[$student->career] ?? ucfirst($student->career) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Estudiantes por Carrera -->
    @php
        $careers = $students->groupBy('career')->sortKeys();
    @endphp
    
    @if($careers->count() > 0)
        @foreach($careers as $career => $studentsInCareer)
            <div class="career-container">
                <div class="career-section">
                    <div class="career-header">
                        <div class="career-title">
                            <h2><i class="fas fa-graduation-cap"></i> {{ $careerTitles[$career] ?? ucfirst($career) }}</h2>
                            <p class="career-description">Gestión de tutores para estudiantes de {{ $careerTitles[$career] ?? ucfirst($career) }}</p>
                        </div>
                        <div class="career-stats">
                            <div class="stat-item">
                                <span class="stat-number">{{ $studentsInCareer->count() }}</span>
                                <span class="stat-label">Estudiantes</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $studentsInCareer->filter(function($student) { return $student->activeTutorAssignment && $student->activeTutorAssignment->teacher; })->count() }}</span>
                                <span class="stat-label">Con Tutor</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $studentsInCareer->filter(function($student) { return !$student->activeTutorAssignment || !$student->activeTutorAssignment->teacher; })->count() }}</span>
                                <span class="stat-label">Sin Tutor</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="career-content">
                        <div class="students-card">
                            <div class="card-header">
                                <h3><i class="fas fa-users"></i> Lista de Estudiantes</h3>
                                <div class="card-actions">
                                    <button class="btn-expand-all" onclick="toggleAllStudents('{{ $career }}')">
                                        <i class="fas fa-expand-alt"></i> Expandir Todo
                                    </button>
                                </div>
                            </div>
                            
                            <div class="students-table-container" id="students-{{ $career }}">
                                <table class="students-table">
                                    <thead>
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Semestre</th>
                                            <th>Tutor Asignado</th>
                                            <th>Documentos</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($studentsInCareer as $student)
                                            <tr>
                                                <td>
                                                    <div class="student-info">
                                                        <strong>{{ $student->name }}</strong>
                                                        <small>{{ $student->student_code }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="semester-badge">{{ $student->semester }}° Semestre</span>
                                                </td>
                                                <td>
                                                    @if($student->activeTutorAssignment && $student->activeTutorAssignment->teacher)
                                                        <div class="tutor-info">
                                                            <strong>{{ $student->activeTutorAssignment->teacher->name }}</strong>
                                                            <small>{{ $student->activeTutorAssignment->teacher->teacher_code }}</small>
                                                        </div>
                                                    @else
                                                        <span class="no-tutor">Sin tutor asignado</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="documents-count">{{ $student->documents->count() }} documentos</span>
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                        @if($student->activeTutorAssignment && $student->activeTutorAssignment->teacher)
                                                            <!-- Formulario para cambiar tutor -->
                                                            <form action="{{ route('coordinador.student_tutor_assignment.assign', $student->id) }}" method="POST" class="tutor-form">
                                                                @csrf
                                                                <select name="teacher_id" class="tutor-select" onchange="this.form.submit()">
                                                                    <option value="">Cambiar tutor...</option>
                                                                    @foreach($teachers as $teacher)
                                                                        <option value="{{ $teacher->id }}" {{ $student->activeTutorAssignment->teacher_id == $teacher->id ? 'selected' : '' }}>
                                                                            {{ $teacher->name }} ({{ $teacher->teacher_code }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </form>
                                                            
                                                            <!-- Botón para remover tutor -->
                                                            <form action="{{ route('coordinador.student_tutor_assignment.remove', $student->id) }}" method="POST" class="remove-form">
                                                                @csrf
                                                                <button type="submit" class="btn-remove-tutor" title="Remover tutor">
                                                                    <i class="fas fa-user-times"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <!-- Formulario para asignar tutor -->
                                                            <form action="{{ route('coordinador.student_tutor_assignment.assign', $student->id) }}" method="POST" class="tutor-form">
                                                                @csrf
                                                                <select name="teacher_id" class="tutor-select" onchange="this.form.submit()">
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
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="no-students">
            <i class="fas fa-inbox"></i>
            <p>No hay estudiantes registrados en el sistema.</p>
        </div>
    @endif

    <!-- Enlaces de Navegación -->
    <div class="navigation-links">
        <a href="{{ route('coordinador.student_tutor_assignment.report') }}" class="btn btn-secondary">
            <i class="fas fa-chart-bar"></i> Ver Reporte
        </a>
        <a href="{{ route('coordinador.professional_practices.index') }}" class="btn btn-primary">
            <i class="fas fa-briefcase"></i> Gestionar Prácticas
        </a>
    </div>
</div>

<style>
.student-tutor-assignment-container {
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

.careers-summary {
    margin-bottom: 30px;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.summary-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.summary-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.summary-header h4 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.2rem;
    font-weight: 600;
}

.total-students {
    background: #3498db;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.summary-stats {
    margin-bottom: 15px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.stat-label {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.stat-value {
    font-weight: 600;
    font-size: 1rem;
}

.stat-value.with-tutor {
    color: #27ae60;
}

.stat-value.without-tutor {
    color: #e74c3c;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: #ecf0f1;
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #27ae60, #2ecc71);
    transition: width 0.3s ease;
}

.filter-section {
    margin-bottom: 30px;
}

.filter-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.filter-card h3 {
    margin-bottom: 15px;
    color: #2c3e50;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #2c3e50;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}

.career-container {
    margin-bottom: 50px;
    background: #f8f9fa;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.career-section {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}

.career-section:nth-child(1) .career-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.career-section:nth-child(2) .career-header {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.career-section:nth-child(3) .career-header {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.career-section:nth-child(4) .career-header {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.career-section:nth-child(5) .career-header {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.career-section:nth-child(6) .career-header {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.career-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px 30px;
    color: white;
    position: relative;
    overflow: hidden;
}

.career-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    z-index: 1;
}

.career-title {
    position: relative;
    z-index: 2;
}

.career-title h2 {
    margin: 0 0 5px 0;
    font-size: 1.8rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.career-description {
    margin: 0;
    font-size: 1rem;
    opacity: 0.9;
    font-weight: 300;
}

.career-stats {
    display: flex;
    gap: 20px;
    position: relative;
    z-index: 2;
}

.career-stats .stat-item {
    text-align: center;
    background: rgba(255, 255, 255, 0.15);
    padding: 12px 16px;
    border-radius: 10px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.career-stats .stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 4px;
}

.career-stats .stat-label {
    font-size: 0.8rem;
    opacity: 0.9;
    font-weight: 500;
}

.career-content {
    padding: 0;
}

.students-card {
    background: white;
    border-radius: 0;
    box-shadow: none;
    overflow: hidden;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.card-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.2rem;
    font-weight: 600;
}

.card-actions {
    display: flex;
    gap: 10px;
}

.btn-expand-all {
    background: #3498db;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-expand-all:hover {
    background: #2980b9;
    transform: translateY(-1px);
}

.students-table-container {
    overflow-x: auto;
}

.students-table {
    width: 100%;
    border-collapse: collapse;
}

.students-table th,
.students-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.students-table th {
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

.no-tutor {
    color: #e74c3c;
    font-style: italic;
}

.documents-count {
    color: #7f8c8d;
    font-size: 0.9rem;
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
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 6px 8px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}

.btn-remove-tutor:hover {
    background-color: #c0392b;
}

.no-students {
    text-align: center;
    padding: 40px;
    color: #7f8c8d;
}

.no-students i {
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
    .summary-grid {
        grid-template-columns: 1fr;
    }
    
    .career-container {
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .career-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
        padding: 20px;
    }
    
    .career-title h2 {
        font-size: 1.5rem;
    }
    
    .career-stats {
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
    }
    
    .career-stats .stat-item {
        padding: 10px 12px;
    }
    
    .career-stats .stat-number {
        font-size: 1.3rem;
    }
    
    .card-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .students-table {
        font-size: 0.9rem;
    }
    
    .students-table th,
    .students-table td {
        padding: 10px 8px;
    }
    
    .actions {
        flex-direction: column;
        gap: 5px;
    }
    
    .tutor-select {
        font-size: 11px;
    }
    
    .btn-remove-tutor {
        font-size: 11px;
        padding: 4px 6px;
    }
}

<script>
function toggleAllStudents(career) {
    const container = document.getElementById(`students-${career}`);
    const button = event.target.closest('.btn-expand-all');
    const icon = button.querySelector('i');
    
    if (container.style.display === 'none') {
        container.style.display = 'block';
        icon.className = 'fas fa-compress-alt';
        button.innerHTML = '<i class="fas fa-compress-alt"></i> Contraer Todo';
    } else {
        container.style.display = 'none';
        icon.className = 'fas fa-expand-alt';
        button.innerHTML = '<i class="fas fa-expand-alt"></i> Expandir Todo';
    }
}

// Inicializar todas las tablas como expandidas
document.addEventListener('DOMContentLoaded', function() {
    const containers = document.querySelectorAll('.students-table-container');
    containers.forEach(container => {
        container.style.display = 'block';
    });
});
</script>

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    gap: 10px;
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
</style>
@endsection 