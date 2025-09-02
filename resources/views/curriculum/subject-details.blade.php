@extends('layouts.student-dashboard')

@section('title', $subject->name . ' - Detalles - ISTPET')

@section('content')
<div class="subject-details-container">
    <!-- Header de la Materia -->
    <div class="subject-header">
        <div class="header-content">
            <h1><i class="fas fa-book"></i> {{ $subject->name }}</h1>
            <div class="subject-meta">
                <span class="career-badge">{{ ucfirst($subject->career) }}</span>
                <span class="year-badge">{{ $subject->academic_year }}° Año</span>
                <span class="credits-badge">{{ $subject->credits }} créditos</span>
                <span class="unit-badge" style="background-color: {{ $subject->curricular_unit_color }}">{{ $subject->curricular_unit_name }}</span>
            </div>
        </div>
    </div>

    <!-- Información de la Materia -->
    <div class="subject-info-section">
        <div class="info-card">
            <h3><i class="fas fa-info-circle"></i> Información de la Materia</h3>
            <div class="info-content">
                <div class="info-item">
                    <label>Descripción:</label>
                    <p>{{ $subject->description }}</p>
                </div>
                <div class="info-item">
                    <label>Unidad Curricular:</label>
                    <span class="unit-tag" style="background-color: {{ $subject->curricular_unit_color }}">{{ $subject->curricular_unit_name }}</span>
                </div>
                <div class="info-item">
                    <label>Año Académico:</label>
                    <span>{{ $subject->academic_year }}° Año</span>
                </div>
                <div class="info-item">
                    <label>Créditos:</label>
                    <span>{{ $subject->credits }} créditos</span>
                </div>
            </div>
        </div>
    </div>

    @if($student)
    <!-- Estado del Estudiante -->
    <div class="student-status-section">
        <div class="status-card">
            <h3><i class="fas fa-user-graduate"></i> Mi Estado en esta Materia</h3>
            @php
                $isEnrolled = $student->subjects->contains($subject->id);
                $grades = $grades ?? collect();
                $averageGrade = $grades->avg('grade');
                $isCompleted = $averageGrade >= 7.0;
            @endphp
            
            <div class="status-content">
                <div class="status-item">
                    <label>Estado de Inscripción:</label>
                    @if($isEnrolled)
                        <span class="status enrolled"><i class="fas fa-check-circle"></i> Inscrito</span>
                    @else
                        <span class="status not-enrolled"><i class="fas fa-times-circle"></i> No Inscrito</span>
                    @endif
                </div>
                
                <div class="status-item">
                    <label>Estado Académico:</label>
                    @if($isCompleted)
                        <span class="status completed"><i class="fas fa-star"></i> Aprobado</span>
                    @elseif($averageGrade)
                        <span class="status in-progress"><i class="fas fa-clock"></i> En Curso</span>
                    @else
                        <span class="status pending"><i class="fas fa-hourglass-half"></i> Pendiente</span>
                    @endif
                </div>
                
                @if($averageGrade)
                <div class="status-item">
                    <label>Promedio Actual:</label>
                    <span class="grade-display">{{ number_format($averageGrade, 1) }}/10</span>
                </div>
                @endif
                
                <div class="status-item">
                    <label>Calificaciones Registradas:</label>
                    <span>{{ $grades->count() }} calificaciones</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Calificaciones -->
    @if($grades->count() > 0)
    <div class="grades-section">
        <div class="grades-card">
            <h3><i class="fas fa-chart-line"></i> Historial de Calificaciones</h3>
            <div class="grades-table-container">
                <table class="grades-table">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Título</th>
                            <th>Calificación</th>
                            <th>Fecha</th>
                            <th>Comentarios</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                        <tr>
                            <td>
                                <span class="grade-type {{ $grade->type }}">
                                    @switch($grade->type)
                                        @case('exam')
                                            <i class="fas fa-file-alt"></i> Examen
                                            @break
                                        @case('homework')
                                            <i class="fas fa-homework"></i> Tarea
                                            @break
                                        @case('project')
                                            <i class="fas fa-project-diagram"></i> Proyecto
                                            @break
                                        @case('participation')
                                            <i class="fas fa-users"></i> Participación
                                            @break
                                        @case('final')
                                            <i class="fas fa-graduation-cap"></i> Final
                                            @break
                                        @default
                                            <i class="fas fa-star"></i> {{ ucfirst($grade->type) }}
                                    @endswitch
                                </span>
                            </td>
                            <td>{{ $grade->title }}</td>
                            <td>
                                <span class="grade-value {{ $grade->grade >= 7.0 ? 'passing' : 'failing' }}">
                                    {{ number_format($grade->grade, 1) }}/10
                                </span>
                            </td>
                            <td>{{ $grade->evaluation_date->format('d/m/Y') }}</td>
                            <td>
                                @if($grade->comments)
                                    <span class="comments" title="{{ $grade->comments }}">
                                        <i class="fas fa-comment"></i> Ver
                                    </span>
                                @else
                                    <span class="no-comments">Sin comentarios</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="no-grades-section">
        <div class="no-grades-card">
            <div class="no-grades-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3>Sin Calificaciones Registradas</h3>
            <p>No hay calificaciones registradas para esta materia aún.</p>
        </div>
    </div>
    @endif
    @endif

    <!-- Acciones -->
    <div class="actions-section">
        <a href="{{ route('estudiante.curriculum.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Malla
        </a>
        @if($student)
        <a href="{{ route('estudiante.curriculum.my-progress') }}" class="btn btn-primary">
            <i class="fas fa-chart-line"></i> Ver Mi Progreso
        </a>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
.subject-details-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

.subject-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    text-align: center;
}

.header-content h1 {
    font-size: 2.5rem;
    margin-bottom: 20px;
}

.subject-meta {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}

.subject-meta span {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.career-badge {
    background: rgba(255, 255, 255, 0.2);
}

.year-badge {
    background: rgba(255, 255, 255, 0.2);
}

.credits-badge {
    background: rgba(255, 255, 255, 0.2);
}

.unit-badge {
    color: white;
}

.subject-info-section {
    margin-bottom: 30px;
}

.info-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.info-card h3 {
    margin-bottom: 20px;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-content {
    display: grid;
    gap: 20px;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.info-item label {
    font-weight: 600;
    color: #495057;
    min-width: 150px;
}

.info-item p {
    margin: 0;
    color: #6c757d;
    line-height: 1.5;
}

.unit-tag {
    color: white;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.9rem;
    font-weight: 500;
}

.student-status-section {
    margin-bottom: 30px;
}

.status-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.status-card h3 {
    margin-bottom: 20px;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 10px;
}

.status-content {
    display: grid;
    gap: 15px;
}

.status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.status-item label {
    font-weight: 600;
    color: #495057;
}

.status {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.status.enrolled {
    background: #d4edda;
    color: #155724;
}

.status.not-enrolled {
    background: #f8d7da;
    color: #721c24;
}

.status.completed {
    background: #cce5ff;
    color: #004085;
}

.status.in-progress {
    background: #fff3cd;
    color: #856404;
}

.status.pending {
    background: #e2e3e5;
    color: #383d41;
}

.grade-display {
    font-size: 1.2rem;
    font-weight: bold;
    color: #007bff;
}

.grades-section {
    margin-bottom: 30px;
}

.grades-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.grades-card h3 {
    margin-bottom: 20px;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 10px;
}

.grades-table-container {
    overflow-x: auto;
}

.grades-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.grades-table th,
.grades-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
}

.grades-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #495057;
}

.grade-type {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.9rem;
    font-weight: 500;
}

.grade-type.exam {
    color: #dc3545;
}

.grade-type.homework {
    color: #fd7e14;
}

.grade-type.project {
    color: #6f42c1;
}

.grade-type.participation {
    color: #20c997;
}

.grade-type.final {
    color: #007bff;
}

.grade-value {
    font-weight: bold;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.9rem;
}

.grade-value.passing {
    background: #d4edda;
    color: #155724;
}

.grade-value.failing {
    background: #f8d7da;
    color: #721c24;
}

.comments {
    color: #007bff;
    cursor: pointer;
}

.no-comments {
    color: #6c757d;
    font-style: italic;
}

.no-grades-section {
    margin-bottom: 30px;
}

.no-grades-card {
    background: white;
    padding: 50px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    text-align: center;
}

.no-grades-icon {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 20px;
}

.no-grades-card h3 {
    color: #495057;
    margin-bottom: 10px;
}

.no-grades-card p {
    color: #6c757d;
    margin: 0;
}

.actions-section {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .subject-meta {
        flex-direction: column;
        align-items: center;
    }
    
    .info-item {
        flex-direction: column;
        gap: 5px;
    }
    
    .status-item {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .grades-table {
        font-size: 0.9rem;
    }
    
    .actions-section {
        flex-direction: column;
        align-items: center;
    }
}
</style>
@endsection 