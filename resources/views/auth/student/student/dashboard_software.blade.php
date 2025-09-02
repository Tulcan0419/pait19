@extends('layouts.student-dashboard')

@section('title', 'Dashboard Estudiante de Software - Tecnológico Traversari - ISTPET')

@section('content')
    <!-- Grid de Tarjetas Informativas -->
    <div class="dashboard-grid">
        
        @if(Auth::guard('student')->user()->semester >= 3)
        <div class="card-item">
            <div class="icon"><i class="fas fa-file-upload"></i></div>
            <h3>{{ Auth::guard('student')->user()->semester >= 4 ? 'Prácticas Profesionales' : 'Prácticas Preprofesionales' }}</h3>
            <p>Sube tus documentos de {{ Auth::guard('student')->user()->semester >= 4 ? 'prácticas profesionales' : 'prácticas preprofesionales' }} aquí.</p>
            <a href="{{ route('estudiante.practices.index') }}" class="btn-view">Subir Documentos</a>
        </div>
        @else
        <div class="card-item disabled-card">
            <div class="icon"><i class="fas fa-lock"></i></div>
            <h3>Prácticas Preprofesionales</h3>
            <p>Disponible a partir del 3° semestre. Tu semestre actual: {{ Auth::guard('student')->user()->semester }}°</p>
            <span class="btn-disabled">No Disponible</span>
        </div>
        @endif
    </div>

    <!-- Sección de Actividad Reciente -->
   
@endsection

@section('scripts')
<script>
    // Scripts específicos para el dashboard de estudiantes de software
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard de estudiante de software cargado');
    });
</script>
@endsection