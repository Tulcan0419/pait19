@extends('layouts.admin-dashboard')

@section('title', 'Estadísticas por Semestre - Admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/statistics.css') }}">
<div class="admin-statistics-container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Estudiantes del {{ $semesterName }}</h1>
                <a href="{{ route('admin.statistics.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Estadísticas
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Total: {{ $students->count() }} estudiantes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>Email</th>
                                    <th>Carrera</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->student_code }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>
                                            @php
                                                $careerNames = [
                                                    'mechanical' => 'Mecánica',
                                                    'software' => 'Desarrollo de Software',
                                                    'education' => 'Educación Básica'
                                                ];
                                                $careerName = $careerNames[$student->career] ?? ucfirst($student->career);
                                            @endphp
                                            <span class="badge bg-info">{{ $careerName }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', ['type' => 'student', 'id' => $student->id]) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Estadísticas por Carrera -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Distribución por Carrera</h5>
                </div>
                <div class="card-body">
                    @php
                        $careerCounts = $students->groupBy('career')->map->count();
                        $careerNames = [
                            'mechanical' => 'Mecánica',
                            'software' => 'Desarrollo de Software',
                            'education' => 'Educación Básica'
                        ];
                    @endphp
                    <div class="row">
                        @foreach(['mechanical', 'software', 'education'] as $career)
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="h4 text-primary">{{ $careerCounts->get($career, 0) }}</div>
                                    <small class="text-muted">{{ $careerNames[$career] }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 