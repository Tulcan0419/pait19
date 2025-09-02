@extends('layouts.teacher-dashboard')

@section('title', 'Resumen de Estudiantes - Profesor')

@section('content')
<link rel="stylesheet" href="{{ asset('css/teacher/students-summary.css') }}">
            <div class="students-summary-container">
                <div class="summary-header">
                    <h1><i class="fas fa-users"></i>Resumen de Estudiantes</h1>
                    <div class="summary-stats">
                        <div class="stat-card">
                            <i class="fas fa-graduation-cap"></i>
                            <div class="stat-info">
                                <span class="stat-number">{{ $totalStudents }}</span>
                                <span class="stat-label">Total de Estudiantes</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-book"></i>
                            <div class="stat-info">
                                <span class="stat-number">{{ $subjects->count() }}</span>
                                <span class="stat-label">Materias Asignadas</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="actions-section">
                    <a href="{{ route('profesor.dashboard') }}" class="btn-view">
                        <i class="fas fa-arrow-left"></i>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
@endsection 