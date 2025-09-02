@extends('layouts.coordinator-dashboard')

@section('title', 'Dashboard Coordinador - Tecnológico Traversari - ISTPET')

@section('content')
    <!-- Grid de Tarjetas Informativas para Coordinador -->
    <div class="coordinator-dashboard-grid">
        <div class="coordinator-card-item">
            <div class="icon"><i class="fas fa-briefcase"></i></div>
            <h3>Prácticas Preprofesionales</h3>
            <p>Coordina y supervisa las prácticas de los estudiantes.</p>
            <a href="{{ route('coordinador.professional_practices.index') }}" class="btn-view">Gestionar Prácticas</a>
        </div>
        <div class="coordinator-card-item">
            <div class="icon"><i class="fas fa-user-tie"></i></div>
            <h3>Asignación de Tutores</h3>
            <p>Asigna profesores como tutores de prácticas pre profesionales.</p>
            <a href="{{ route('coordinador.student_tutor_assignment.index') }}" class="btn-view">Gestionar Tutores por Estudiante</a>
        </div>
    </div>

    <!-- Sección de Estadísticas de Coordinación -->
    

    <!-- Sección de Actividad Reciente -->
    
@endsection

@section('scripts')
<script>
    // Scripts específicos para el dashboard de coordinadores
    document.addEventListener('DOMContentLoaded', function() {
        // Aquí puedes agregar funcionalidades específicas para coordinadores
        console.log('Dashboard de coordinador cargado');
    });
</script>
@endsection 