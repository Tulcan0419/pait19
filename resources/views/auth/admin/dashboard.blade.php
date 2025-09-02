@extends('layouts.admin-dashboard')

@section('title', 'Dashboard Administrador - Tecnológico Traversari - ISTPET')

@section('content')
    <!-- Grid de Tarjetas Informativas para Administrador -->
    <div class="admin-dashboard-grid">
        <div class="admin-card-item">
            <div class="icon"><i class="fas fa-users"></i></div>
            <h3>Gestionar Usuarios</h3>
            <p>Administra estudiantes, profesores y coordinadores del sistema.</p>
            <a href="{{ route('admin.users.index') }}" class="btn-view">Gestionar Usuarios</a>
        </div>
        <div class="admin-card-item">
            <div class="icon"><i class="fas fa-chart-pie"></i></div>
            <h3>Estadísticas del Sistema</h3>
            <p>Visualiza estadísticas generales y reportes del instituto.</p>
            <a href="{{ route('admin.statistics.index') }}" class="btn-view">Ver Estadísticas</a>
        </div>
        <div class="admin-card-item">
            <div class="icon"><i class="fas fa-cog"></i></div>
            <h3>Configuración del Sistema</h3>
            <p>Configura parámetros y ajustes del sistema.</p>
            <a href="{{ route('admin.settings') }}" class="btn-view">Configuración</a>
        </div>
    </div>

    <!-- Sección de Estadísticas Rápidas -->
    
    <!-- Sección de Actividad Reciente -->
    
@endsection

@section('scripts')
<script>
    // Scripts específicos para el dashboard de administradores
    document.addEventListener('DOMContentLoaded', function() {
        // Aquí puedes agregar funcionalidades específicas para administradores
        console.log('Dashboard de administrador cargado');
    });
</script>
@endsection