@extends('layouts.admin-dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Asignación Masiva de Materias</h1>
            <a href="{{ route('admin.subject-assignments.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.subject-assignments.bulk') }}" method="POST" id="bulk-assignment-form">
                @csrf

                <!-- Selección de Profesores -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">1. Seleccionar Profesores</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($teachers as $teacher)
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition duration-200">
                            <input type="checkbox" name="teacher_ids[]" value="{{ $teacher->id }}" 
                                   class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <div>
                                <div class="font-medium text-gray-900">{{ $teacher->name }}</div>
                                <div class="text-sm text-gray-500">{{ $teacher->email }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Selección de Materias -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">2. Seleccionar Materias</h2>
                    
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
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">{{ $i }}° semestre</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unidad Curricular</label>
                            <select id="unit-filter" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="">Todas las unidades</option>
                                <option value="basica">Básica</option>
                                <option value="profesional">Profesional</option>
                                <option value="integracion">Integración Curricular</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button" id="select-all-subjects" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition duration-200">
                                Seleccionar Todas
                            </button>
                        </div>
                        <div class="flex items-end">
                            <button type="button" id="deselect-all-subjects" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm transition duration-200">
                                Deseleccionar Todas
                            </button>
                        </div>
                    </div>

                    <!-- Cuadrícula de materias -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="subjects-grid">
                        @foreach($subjects as $subject)
                        <label class="subject-checkbox flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition duration-200"
                               data-career="{{ $subject->career }}" 
                               data-semester="{{ $subject->semester }}" 
                               data-unit="{{ $subject->curricular_unit }}">
                            <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" 
                                   class="mt-1 mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 text-sm">{{ $subject->name }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-medium
                                        {{ $subject->curricular_unit === 'basica' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $subject->curricular_unit === 'profesional' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $subject->curricular_unit === 'integracion' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ $subject->curricular_unit_name }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ ucfirst($subject->career) }} - {{ $subject->semester }}° semestre
                                </div>
                                <div class="text-xs text-gray-500">{{ $subject->credits }} créditos</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Configuración de la Asignación -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">3. Configuración de la Asignación</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="academic_period" class="block text-sm font-medium text-gray-700 mb-2">
                                Período Académico <span class="text-red-500">*</span>
                            </label>
                            <select name="academic_period" id="academic_period" required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecciona un período</option>
                                @foreach($academicPeriods as $period)
                                    <option value="{{ $period }}">{{ $period }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Estado <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecciona un estado</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                                <option value="pending">Pendiente</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Resumen de la Asignación -->
                <div class="mb-8 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Resumen de la Asignación</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="font-medium">Profesores seleccionados:</span>
                            <span id="selected-teachers-count" class="text-blue-600">0</span>
                        </div>
                        <div>
                            <span class="font-medium">Materias seleccionadas:</span>
                            <span id="selected-subjects-count" class="text-blue-600">0</span>
                        </div>
                        <div>
                            <span class="font-medium">Total de asignaciones:</span>
                            <span id="total-assignments" class="text-green-600 font-bold">0</span>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.subject-assignments.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                        Cancelar
                    </a>
                    <button type="submit" id="submit-btn" disabled
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-save mr-2"></i>Crear Asignaciones
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const teacherCheckboxes = document.querySelectorAll('input[name="teacher_ids[]"]');
    const subjectCheckboxes = document.querySelectorAll('input[name="subject_ids[]"]');
    const careerFilter = document.getElementById('career-filter');
    const semesterFilter = document.getElementById('semester-filter');
    const unitFilter = document.getElementById('unit-filter');
    const selectAllBtn = document.getElementById('select-all-subjects');
    const deselectAllBtn = document.getElementById('deselect-all-subjects');
    const submitBtn = document.getElementById('submit-btn');

    // Contadores
    const selectedTeachersCount = document.getElementById('selected-teachers-count');
    const selectedSubjectsCount = document.getElementById('selected-subjects-count');
    const totalAssignments = document.getElementById('total-assignments');

    // Función para actualizar contadores
    function updateCounters() {
        const selectedTeachers = document.querySelectorAll('input[name="teacher_ids[]"]:checked').length;
        const selectedSubjects = document.querySelectorAll('input[name="subject_ids[]"]:checked').length;
        const total = selectedTeachers * selectedSubjects;

        selectedTeachersCount.textContent = selectedTeachers;
        selectedSubjectsCount.textContent = selectedSubjects;
        totalAssignments.textContent = total;

        // Habilitar/deshabilitar botón de envío
        submitBtn.disabled = selectedTeachers === 0 || selectedSubjects === 0;
    }

    // Event listeners para checkboxes
    teacherCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCounters);
    });

    subjectCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCounters);
    });

    // Filtros
    function filterSubjects() {
        const selectedCareer = careerFilter.value;
        const selectedSemester = semesterFilter.value;
        const selectedUnit = unitFilter.value;

        document.querySelectorAll('.subject-checkbox').forEach(checkbox => {
            const career = checkbox.dataset.career;
            const semester = checkbox.dataset.semester;
            const unit = checkbox.dataset.unit;

            const showCareer = !selectedCareer || career === selectedCareer;
            const showSemester = !selectedSemester || semester === selectedSemester;
            const showUnit = !selectedUnit || unit === selectedUnit;

            if (showCareer && showSemester && showUnit) {
                checkbox.style.display = 'block';
            } else {
                checkbox.style.display = 'none';
            }
        });
    }

    careerFilter.addEventListener('change', filterSubjects);
    semesterFilter.addEventListener('change', filterSubjects);
    unitFilter.addEventListener('change', filterSubjects);

    // Botones de selección masiva
    selectAllBtn.addEventListener('click', function() {
        document.querySelectorAll('.subject-checkbox:not([style*="display: none"]) input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = true;
        });
        updateCounters();
    });

    deselectAllBtn.addEventListener('click', function() {
        document.querySelectorAll('input[name="subject_ids[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        updateCounters();
    });

    // Validación del formulario
    document.getElementById('bulk-assignment-form').addEventListener('submit', function(e) {
        const selectedTeachers = document.querySelectorAll('input[name="teacher_ids[]"]:checked').length;
        const selectedSubjects = document.querySelectorAll('input[name="subject_ids[]"]:checked').length;
        const academicPeriod = document.getElementById('academic_period').value;
        const status = document.getElementById('status').value;

        if (selectedTeachers === 0) {
            e.preventDefault();
            alert('Por favor selecciona al menos un profesor.');
            return;
        }

        if (selectedSubjects === 0) {
            e.preventDefault();
            alert('Por favor selecciona al menos una materia.');
            return;
        }

        if (!academicPeriod) {
            e.preventDefault();
            alert('Por favor selecciona un período académico.');
            return;
        }

        if (!status) {
            e.preventDefault();
            alert('Por favor selecciona un estado.');
            return;
        }

        // Confirmar antes de enviar
        const total = selectedTeachers * selectedSubjects;
        if (!confirm(`¿Estás seguro de que quieres crear ${total} asignaciones?`)) {
            e.preventDefault();
        }
    });

    // Inicializar contadores
    updateCounters();
});
</script>

<style>
.subject-checkbox:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.subject-checkbox input[type="checkbox"]:checked + div {
    background-color: #eff6ff;
}

.subject-checkbox input[type="checkbox"]:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}
</style>
@endsection 