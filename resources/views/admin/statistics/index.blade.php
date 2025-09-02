@extends('layouts.admin-dashboard')

@section('title', 'Estadísticas - Admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/statistics.css') }}">
<div class="admin-statistics-container">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Estadísticas de Estudiantes</h1>
            
            <!-- Estadísticas Generales -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total de Estudiantes</h5>
                            <h2 class="text-primary">{{ $generalStats['total_students'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Promedio de Semestre</h5>
                            <h2 class="text-success">{{ $generalStats['average_semester'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Distribución por Semestre</h5>
                            <div class="small">
                                @foreach($generalStats['semester_distribution'] as $dist)
                                    <div>Semestre {{ $dist->semester }}: {{ $dist->count }} estudiantes</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas por Carrera -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Mecánica</h5>
                        </div>
                        <div class="card-body">
                            <h3 class="text-info">{{ $statistics['mechanical']['total'] }} estudiantes</h3>
                            <div class="mt-3">
                                @foreach($statistics['mechanical']['by_semester'] as $semester)
                                    <div class="d-flex justify-content-between">
                                        <span>Semestre {{ $semester->semester }}:</span>
                                        <span class="badge bg-primary">{{ $semester->count }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('admin.statistics.by_career', 'mechanical') }}" class="btn btn-sm btn-outline-primary mt-2">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Desarrollo de Software</h5>
                        </div>
                        <div class="card-body">
                            <h3 class="text-success">{{ $statistics['software']['total'] }} estudiantes</h3>
                            <div class="mt-3">
                                @foreach($statistics['software']['by_semester'] as $semester)
                                    <div class="d-flex justify-content-between">
                                        <span>Semestre {{ $semester->semester }}:</span>
                                        <span class="badge bg-success">{{ $semester->count }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('admin.statistics.by_career', 'software') }}" class="btn btn-sm btn-outline-success mt-2">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Educación Básica</h5>
                        </div>
                        <div class="card-body">
                            <h3 class="text-warning">{{ $statistics['education']['total'] }} estudiantes</h3>
                            <div class="mt-3">
                                @foreach($statistics['education']['by_semester'] as $semester)
                                    <div class="d-flex justify-content-between">
                                        <span>Semestre {{ $semester->semester }}:</span>
                                        <span class="badge bg-warning">{{ $semester->count }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('admin.statistics.by_career', 'education') }}" class="btn btn-sm btn-outline-warning mt-2">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enlaces Rápidos -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Enlaces Rápidos</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @for($i = 1; $i <= 4; $i++)
                                    <div class="col-md-3 col-sm-6 col-6 mb-2">
                                        <a href="{{ route('admin.statistics.by_semester', $i) }}" class="btn btn-outline-secondary btn-sm w-100">
                                            {{ $i }}° Semestre
                                        </a>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 