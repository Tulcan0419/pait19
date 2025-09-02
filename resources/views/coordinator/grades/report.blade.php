@extends('layouts.coordinator-dashboard')

@section('title', 'Reporte de Calificaciones')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Reporte General de Calificaciones</h4>
                <div class="page-title-right">
                    <button class="btn btn-success" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Exportar a Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas generales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Total Calificaciones</p>
                            <h4 class="mb-0">{{ $statistics['total_grades'] }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="fas fa-star font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Promedio General</p>
                            <h4 class="mb-0">{{ round($statistics['average_grade'], 2) }}/10</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success align-self-center">
                                <span class="avatar-title">
                                    <i class="fas fa-chart-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Calificación Más Alta</p>
                            <h4 class="mb-0">{{ $statistics['highest_grade'] }}/10</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning align-self-center">
                                <span class="avatar-title">
                                    <i class="fas fa-trophy font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Calificación Más Baja</p>
                            <h4 class="mb-0">{{ $statistics['lowest_grade'] }}/10</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-danger align-self-center">
                                <span class="avatar-title">
                                    <i class="fas fa-exclamation-triangle font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="subject_filter" class="form-label">Filtrar por Materia</label>
                            <select id="subject_filter" class="form-select">
                                <option value="">Todas las materias</option>
                                @foreach($grades->pluck('subject.name')->unique() as $subjectName)
                                <option value="{{ $subjectName }}">{{ $subjectName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="teacher_filter" class="form-label">Filtrar por Profesor</label>
                            <select id="teacher_filter" class="form-select">
                                <option value="">Todos los profesores</option>
                                @foreach($grades->pluck('teacher.name')->unique() as $teacherName)
                                <option value="{{ $teacherName }}">{{ $teacherName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="type_filter" class="form-label">Filtrar por Tipo</label>
                            <select id="type_filter" class="form-select">
                                <option value="">Todos los tipos</option>
                                <option value="exam">Examen</option>
                                <option value="homework">Tarea</option>
                                <option value="project">Proyecto</option>
                                <option value="participation">Participación</option>
                                <option value="final">Final</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_filter" class="form-label">Filtrar por Fecha</label>
                            <input type="date" id="date_filter" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de calificaciones -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table text-primary"></i>
                        Calificaciones Detalladas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="gradesTable">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Estudiante</th>
                                    <th>Materia</th>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Calificación</th>
                                    <th>Profesor</th>
                                    <th>Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grades as $grade)
                                <tr>
                                    <td>{{ $grade->evaluation_date->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded-circle p-2 me-3">
                                                <i class="fas fa-user text-secondary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $grade->student->name }}</h6>
                                                <small class="text-muted">{{ $grade->student->student_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $grade->subject->name }}</span>
                                    </td>
                                    <td><strong>{{ $grade->title }}</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $grade->type === 'exam' ? 'danger' : ($grade->type === 'final' ? 'dark' : 'info') }}">
                                            {{ ucfirst($grade->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $grade->grade >= 7 ? 'success' : ($grade->grade >= 5 ? 'warning' : 'danger') }} fs-6">
                                            {{ $grade->grade }}/10
                                        </span>
                                    </td>
                                    <td>{{ $grade->teacher->name }}</td>
                                    <td>
                                        @if($grade->comments)
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                data-bs-toggle="tooltip" data-bs-placement="top" 
                                                title="{{ $grade->comments }}">
                                            <i class="fas fa-comment"></i>
                                        </button>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Análisis por materia -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-primary"></i>
                        Análisis por Materia
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Materia</th>
                                    <th>Total Calificaciones</th>
                                    <th>Promedio</th>
                                    <th>Calificación Más Alta</th>
                                    <th>Calificación Más Baja</th>
                                    <th>Estudiantes Evaluados</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grades->groupBy('subject.name') as $subjectName => $subjectGrades)
                                @php
                                    $average = $subjectGrades->avg('grade');
                                    $highest = $subjectGrades->max('grade');
                                    $lowest = $subjectGrades->min('grade');
                                    $studentsCount = $subjectGrades->pluck('student_id')->unique()->count();
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $subjectName }}</span>
                                    </td>
                                    <td>{{ $subjectGrades->count() }}</td>
                                    <td>
                                        <span class="badge bg-{{ $average >= 7 ? 'success' : ($average >= 5 ? 'warning' : 'danger') }}">
                                            {{ round($average, 2) }}/10
                                        </span>
                                    </td>
                                    <td>{{ $highest }}/10</td>
                                    <td>{{ $lowest }}/10</td>
                                    <td>{{ $studentsCount }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Filtros
    const subjectFilter = document.getElementById('subject_filter');
    const teacherFilter = document.getElementById('teacher_filter');
    const typeFilter = document.getElementById('type_filter');
    const dateFilter = document.getElementById('date_filter');
    const table = document.getElementById('gradesTable');

    function filterTable() {
        const subjectValue = subjectFilter.value.toLowerCase();
        const teacherValue = teacherFilter.value.toLowerCase();
        const typeValue = typeFilter.value.toLowerCase();
        const dateValue = dateFilter.value;

        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (let row of rows) {
            const cells = row.getElementsByTagName('td');
            const subject = cells[2].textContent.toLowerCase();
            const teacher = cells[6].textContent.toLowerCase();
            const type = cells[4].textContent.toLowerCase();
            const date = cells[0].textContent;

            const subjectMatch = !subjectValue || subject.includes(subjectValue);
            const teacherMatch = !teacherValue || teacher.includes(teacherValue);
            const typeMatch = !typeValue || type.includes(typeValue);
            const dateMatch = !dateValue || date.includes(dateValue);

            if (subjectMatch && teacherMatch && typeMatch && dateMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    subjectFilter.addEventListener('change', filterTable);
    teacherFilter.addEventListener('change', filterTable);
    typeFilter.addEventListener('change', filterTable);
    dateFilter.addEventListener('change', filterTable);
});

function exportToExcel() {
    // Implementar exportación a Excel
    alert('Función de exportación a Excel en desarrollo');
}
</script>
@endpush 