@extends('layouts.student-dashboard')

@section('title', 'Dashboard Estudiante de Educación - Tecnológico Traversari - ISTPET')

@section('content')
    <!-- Grid de Tarjetas Informativas -->
    <div class="dashboard-grid">
        <div class="card-item">
            <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <h3>Asignaturas de Educación</h3>
            <p>Tienes 6 asignaturas activas este semestre ({{ $student->semester ?? Auth::guard('student')->user()->semester }}° Semestre).</p>
            <a href="#" class="btn-view">Ver Asignaturas</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-graduation-cap"></i></div>
            <h3>Prácticas Docentes</h3>
            <p>3 prácticas docentes en marcha para el {{ $student->semester ?? Auth::guard('student')->user()->semester }}° Semestre.</p>
            <a href="#" class="btn-view">Ver Prácticas</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-star"></i></div>
            <h3>Promedio General</h3>
            <p>Tu promedio actual es de 9.0/10.</p>
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
                    <h4>Práctica docente completada</h4>
                    <p>Práctica de Matemáticas en 3er Grado - Completada el 10 de Julio</p>
                    <span class="activity-time">Hace 1 día</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-upload"></i>
                </div>
                <div class="activity-content">
                    <h4>Documento subido</h4>
                    <p>Plan de Clase de Ciencias - Entregado el 12 de Julio</p>
                    <span class="activity-time">Hace 2 días</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-comment-dots"></i>
                </div>
                <div class="activity-content">
                    <h4>Nuevo mensaje</h4>
                    <p>Profesora María López - Sobre la clase de Pedagogía</p>
                    <span class="activity-time">Hace 3 días</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Scripts específicos para el dashboard de estudiantes de educación
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard de estudiante de educación cargado');
    });
</script>
@endsection