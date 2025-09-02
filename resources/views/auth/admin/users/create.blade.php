@extends('layouts.admin-dashboard')

@section('title', 'Crear Usuario - Admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/users.create.css') }}">
<div class="admin-users-container">
    <div class="admin-users-header">
        <h1>Crear Usuario</h1>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.users.index') }}" class="btn-corporate-secondary">Volver a Usuarios</a>
        </div>
    </div>
    <div class="admin-users-cards" style="justify-content:center;">
        <div class="user-card admin" style="min-width:350px;max-width:500px;width:100%;">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="type" class="form-label">Tipo de usuario</label>
                    <select name="type" id="type" class="form-control" required onchange="mostrarCampos()">
                        <optionM value="admin">Administrador</optionM>
                        <option value="student">Estudiante</option>
                        <option value="teacher">Profesor</option>
                        <option value="coordinator">Coordinador</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="mb-3" id="student_code_field" style="display:none;">
                    <label for="student_code" class="form-label">Código de Estudiante</label>
                    <input type="text" name="student_code" id="student_code" class="form-control">
                </div>
                <div class="mb-3" id="teacher_code_field" style="display:none;">
                    <label for="teacher_code" class="form-label">Código de Profesor</label>
                    <input type="text" name="teacher_code" id="teacher_code" class="form-control">
                </div>
                <div class="mb-3" id="career_field" style="display:none;">
                    <label for="career" class="form-label">Carrera</label>
                    <select name="career" id="career" class="form-control">
                        <option value="mechanical">Mecánica</option>
                        <option value="software">Desarrollo de Software</option>
                        <option value="education">Educación Básica</option>
                    </select>
                </div>
                <div class="mb-3" id="semester_field" style="display:none;">
                    <label for="semester" class="form-label">Semestre</label>
                    <select name="semester" id="semester" class="form-control">
                        <option value="1">Primer Semestre</option>
                        <option value="2">Segundo Semestre</option>
                        <option value="3">Tercer Semestre</option>
                        <option value="4">Cuarto Semestre</option>
                    </select>
                </div>
                <div class="mb-3" id="subjects_field" style="display:none;">
                    <label class="form-label">Materias que puede impartir</label>
                    
                    <!-- Filtros para materias -->
                    <div class="mb-4 flex flex-wrap gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Carrera</label>
                            <select id="career-filter" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="">Todas las carreras</option>
                                <option value="software">Desarrollo de Software</option>
                                <option value="mechanical">Mecánica</option>
                                <option value="education">Educación Básica</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Semestre</label>
                            <select id="semester-filter" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="">Todos los semestres</option>
                                @for($i = 1; $i <= 4; $i++)
                                    <option value="{{ $i }}">{{ $i }}° semestre</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn-corporate-edit">Crear</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-corporate-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function mostrarCampos() {
    var type = document.getElementById('type').value;
    document.getElementById('student_code_field').style.display = (type === 'student') ? '' : 'none';
    document.getElementById('career_field').style.display = (type === 'student') ? '' : 'none';
    document.getElementById('semester_field').style.display = (type === 'student') ? '' : 'none';
    document.getElementById('teacher_code_field').style.display = (type === 'teacher') ? '' : 'none';
    document.getElementById('subjects_field').style.display = (type === 'teacher') ? '' : 'none';
}

// Funcionalidad para filtros y selección de materias
document.addEventListener('DOMContentLoaded', function() {
    mostrarCampos();
    
    // Elementos del DOM
    const careerFilter = document.getElementById('career-filter');
    const semesterFilter = document.getElementById('semester-filter');
    const unitFilter = document.getElementById('unit-filter');
    const subjectsGrid = document.getElementById('subjects-grid');
    const subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
    const selectAllBtn = document.getElementById('select-all-subjects');
    const deselectAllBtn = document.getElementById('deselect-all-subjects');
    const selectedCount = document.getElementById('selected-subjects-count');
    
    // Función para filtrar materias
    function filterSubjects() {
        const career = careerFilter.value;
        const semester = semesterFilter.value;
        const unit = unitFilter.value;
        
        subjectCheckboxes.forEach(checkbox => {
            const subjectCareer = checkbox.dataset.career;
            const subjectSemester = checkbox.dataset.semester;
            const subjectUnit = checkbox.dataset.unit;
            
            const careerMatch = !career || subjectCareer === career;
            const semesterMatch = !semester || subjectSemester === semester;
            const unitMatch = !unit || subjectUnit === unit;
            
            if (careerMatch && semesterMatch && unitMatch) {
                checkbox.style.display = 'flex';
            } else {
                checkbox.style.display = 'none';
            }
        });
    }
    
    // Función para actualizar contador
    function updateSelectedCount() {
        const visibleCheckboxes = Array.from(subjectCheckboxes).filter(checkbox => 
            checkbox.style.display !== 'none'
        );
        const selectedCheckboxes = visibleCheckboxes.filter(checkbox => 
            checkbox.querySelector('input[type="checkbox"]').checked
        );
        selectedCount.textContent = selectedCheckboxes.length;
    }
    
    // Event listeners para filtros
    careerFilter.addEventListener('change', filterSubjects);
    semesterFilter.addEventListener('change', filterSubjects);
    unitFilter.addEventListener('change', filterSubjects);
    
    // Event listeners para checkboxes
    subjectCheckboxes.forEach(checkbox => {
        const input = checkbox.querySelector('input[type="checkbox"]');
        input.addEventListener('change', updateSelectedCount);
    });
    
    // Botones de seleccionar/deseleccionar todo
    selectAllBtn.addEventListener('click', function() {
        const visibleCheckboxes = Array.from(subjectCheckboxes).filter(checkbox => 
            checkbox.style.display !== 'none'
        );
        visibleCheckboxes.forEach(checkbox => {
            checkbox.querySelector('input[type="checkbox"]').checked = true;
        });
        updateSelectedCount();
    });
    
    deselectAllBtn.addEventListener('click', function() {
        const visibleCheckboxes = Array.from(subjectCheckboxes).filter(checkbox => 
            checkbox.style.display !== 'none'
        );
        visibleCheckboxes.forEach(checkbox => {
            checkbox.querySelector('input[type="checkbox"]').checked = false;
        });
        updateSelectedCount();
    });
    
    // Inicializar contador
    updateSelectedCount();
});

document.getElementById('type').addEventListener('change', mostrarCampos);
</script>
@endsection 