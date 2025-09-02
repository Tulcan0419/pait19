@extends('layouts.student-dashboard')

@section('title', 'Dashboard Estudiante de Mecánica - Tecnológico Traversari - ISTPET')

@section('content')
    <!-- Grid de Tarjetas Informativas -->
    <div class="dashboard-grid">
        <div class="card-item">
            <div class="icon"><i class="fas fa-cogs"></i></div>
            <h3>Asignaturas de Mecánica</h3>
            <p>Tienes 6 asignaturas activas este semestre ({{ $student->semester ?? Auth::guard('student')->user()->semester }}° Semestre).</p>
            <a href="#" class="btn-view">Ver Asignaturas</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-tools"></i></div>
            <h3>Proyectos en Curso</h3>
            <p>3 proyectos de mecánica en marcha para el {{ $student->semester ?? Auth::guard('student')->user()->semester }}° Semestre.</p>
            <a href="#" class="btn-view">Ver Proyectos</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-star"></i></div>
            <h3>Promedio General</h3>
            <p>Tu promedio actual es de 8.8/10.</p>
            <a href="#" class="btn-view">Ver Calificaciones</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-file-upload"></i></div>
            <h3>{{ Auth::guard('student')->user()->semester >= 4 ? 'Prácticas Profesionales' : 'Prácticas Preprofesionales' }}</h3>
            <p>Sube tus documentos de {{ Auth::guard('student')->user()->semester >= 4 ? 'prácticas profesionales' : 'prácticas preprofesionales' }} aquí.</p>
            <a href="{{ route('estudiante.practices.index') }}" class="btn-view">Subir Documentos</a>
        </div>
    </div>

    <!-- Sección de Actividad Reciente -->
    <div class="recent-activity">
        <h2>Actividad Reciente</h2>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="activity-content">
                    <h4>Proyecto entregado</h4>
                    <p>Diseño de Sistema Mecánico - Entregado el 10 de Julio</p>
                    <span class="activity-time">Hace 1 día</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-upload"></i>
                </div>
                <div class="activity-content">
                    <h4>Documento subido</h4>
                    <p>Informe de Prácticas de Taller - Entregado el 12 de Julio</p>
                    <span class="activity-time">Hace 2 días</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-comment-dots"></i>
                </div>
                <div class="activity-content">
                    <h4>Nuevo mensaje</h4>
                    <p>Profesor Carlos Ruiz - Sobre la clase de Termodinámica</p>
                    <span class="activity-time">Hace 3 días</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Scripts específicos para el dashboard de estudiantes de mecánica
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard de estudiante de mecánica cargado');
    });
</script>
@endsection