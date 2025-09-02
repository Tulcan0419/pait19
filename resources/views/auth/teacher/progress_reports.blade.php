@extends('layouts.teacher-dashboard')

@section('title', 'Reportes de Progreso - Profesor')

@section('content')
    <!-- Header Section -->
    <div class="content-header">
        <div class="header-content">
            <h1><i class="fas fa-chart-line"></i> Reportes de Progreso</h1>
            <p>Análisis y estadísticas del progreso de tus estudiantes</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('profesor.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>Total Estudiantes</h3>
                <p class="stat-number">{{ $totalStudents ?? 0 }}</p>
                <span class="stat-label">En todas tus materias</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-content">
                <h3>Materias Activas</h3>
                <p class="stat-number">{{ $activeSubjects ?? 0 }}</p>
                <span class="stat-label">Este período</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="stat-content">
                <h3>Promedio General</h3>
                <p class="stat-number">{{ number_format($averageGrade ?? 0, 1) }}</p>
                <span class="stat-label">De todos los estudiantes</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="stat-content">
                <h3>Mejor Promedio</h3>
                <p class="stat-number">{{ number_format($bestAverage ?? 0, 1) }}</p>
                <span class="stat-label">Por materia</span>
            </div>
        </div>
    </div>

    <!-- Progress Charts Section -->
    <div class="charts-section">
        <div class="chart-container">
            <h2><i class="fas fa-chart-pie"></i> Distribución por Materia</h2>
            <div class="chart-placeholder">
                <p>Gráfico de distribución de estudiantes por materia</p>
                <div class="chart-info">
                    <span class="info-item">Mecánica: {{ $mechanicalStudents ?? 0 }} estudiantes</span>
                    <span class="info-item">Software: {{ $softwareStudents ?? 0 }} estudiantes</span>
                    <span class="info-item">Educación: {{ $educationStudents ?? 0 }} estudiantes</span>
                </div>
            </div>
        </div>

        <div class="chart-container">
            <h2><i class="fas fa-chart-line"></i> Tendencia de Calificaciones</h2>
            <div class="chart-placeholder">
                <p>Gráfico de tendencia de calificaciones por período</p>
                <div class="chart-info">
                    <span class="info-item">Promedio actual: {{ number_format($currentAverage ?? 0, 1) }}</span>
                    <span class="info-item">Promedio anterior: {{ number_format($previousAverage ?? 0, 1) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Performance Table -->
    <div class="table-section">
        <h2><i class="fas fa-table"></i> Rendimiento por Materia</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Estudiantes</th>
                        <th>Promedio</th>
                        <th>Mejor Nota</th>
                        <th>Peor Nota</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects ?? [] as $subject)
                    <tr>
                        <td>
                            <div class="subject-info">
                                <strong>{{ $subject->name }}</strong>
                                <small>{{ $subject->curricular_unit_name ?? 'N/A' }}</small>
                            </div>
                        </td>
                        <td>{{ $subject->students_count ?? 0 }}</td>
                        <td>{{ number_format($subject->average_grade ?? 0, 1) }}</td>
                        <td>{{ number_format($subject->highest_grade ?? 0, 1) }}</td>
                        <td>{{ number_format($subject->lowest_grade ?? 0, 1) }}</td>
                        <td>
                            <a href="{{ route('profesor.grades.index') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Ver Calificaciones
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <p>No hay materias asignadas para mostrar estadísticas.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Export Options -->
    <div class="export-section">
        <h2><i class="fas fa-download"></i> Exportar Reportes</h2>
        <div class="export-options">
            <button class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </button>
            <button class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </button>
            <button class="btn btn-info">
                <i class="fas fa-print"></i> Imprimir Reporte
            </button>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Aquí se pueden agregar scripts para gráficos interactivos
        console.log('Reportes de progreso cargados');
        
        // Placeholder para futuras funcionalidades de gráficos
        // Se pueden integrar librerías como Chart.js o D3.js
    });
</script>
@endsection
