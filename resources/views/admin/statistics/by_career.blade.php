@extends('layouts.admin-dashboard')

@section('title', 'Estadísticas por Carrera - Admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/statistics.css') }}">
<div class="admin-statistics-container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Estudiantes de {{ $careerName }}</h1>
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
                                    <th>Semestre</th>
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
                                            <span class="badge bg-primary">{{ $student->semester }}° Semestre</span>
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

            <!-- Estadísticas por Semestre -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Distribución por Semestre</h5>
                </div>
                <div class="card-body">
                    @php
                        $semesterCounts = $students->groupBy('semester')->map->count();
                    @endphp
                    <div class="row">
                        @for($i = 1; $i <= 4; $i++)
                            <div class="col-md-3 col-sm-6 col-6 mb-2">
                                <div class="text-center">
                                    <div class="h4 text-primary">{{ $semesterCounts->get($i, 0) }}</div>
                                    <small class="text-muted">{{ $i }}° Semestre</small>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 