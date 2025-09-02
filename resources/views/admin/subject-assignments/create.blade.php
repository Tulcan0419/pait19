@extends('layouts.admin-dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Nueva Asignación de Materia</h1>
            <a href="{{ route('admin.subject-assignments.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.subject-assignments.store') }}" method="POST">
                @csrf

                <!-- Profesor -->
                <div class="mb-6">
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Profesor <span class="text-red-500">*</span>
                    </label>
                    <select name="teacher_id" id="teacher_id" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('teacher_id') border-red-500 @enderror">
                        <option value="">Selecciona un profesor</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Materia -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Materia <span class="text-red-500">*</span>
                    </label>
                    
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
                    </div>

                    <!-- Cuadrícula de materias -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="subjects-grid">
                        @foreach($subjects as $subject)
                        <label class="subject-checkbox flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition duration-200"
                               data-career="{{ $subject->career }}" 
                               data-semester="{{ $subject->semester }}" 
                               data-unit="{{ $subject->curricular_unit }}">
                            <input type="radio" name="subject_id" value="{{ $subject->id }}" 
                                   class="mt-1 mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                   {{ old('subject_id') == $subject->id ? 'checked' : '' }}>
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
                    @error('subject_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Período Académico -->
                <div class="mb-6">
                    <label for="academic_period" class="block text-sm font-medium text-gray-700 mb-2">
                        Período Académico <span class="text-red-500">*</span>
                    </label>
                    <select name="academic_period" id="academic_period" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('academic_period') border-red-500 @enderror">
                        <option value="">Selecciona un período</option>
                        @foreach($academicPeriods as $period)
                            <option value="{{ $period }}" {{ old('academic_period') == $period ? 'selected' : '' }}>
                                {{ $period }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_period')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                        <option value="">Selecciona un estado</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Máximo de Estudiantes -->
                <div class="mb-6">
                    <label for="max_students" class="block text-sm font-medium text-gray-700 mb-2">
                        Máximo de Estudiantes
                    </label>
                    <input type="number" name="max_students" id="max_students" min="1"
                           value="{{ old('max_students') }}"
                           placeholder="Dejar vacío para sin límite"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_students') border-red-500 @enderror">
                    @error('max_students')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Deja vacío si no hay límite de estudiantes</p>
                </div>

                <!-- Comentarios -->
                <div class="mb-6">
                    <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">
                        Comentarios
                    </label>
                    <textarea name="comments" id="comments" rows="3"
                              placeholder="Comentarios adicionales sobre la asignación..."
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('comments') border-red-500 @enderror">{{ old('comments') }}</textarea>
                    @error('comments')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.subject-assignments.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                        <i class="fas fa-save mr-2"></i>Crear Asignación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación en tiempo real
    const teacherSelect = document.getElementById('teacher_id');
    const periodSelect = document.getElementById('academic_period');
    const careerFilter = document.getElementById('career-filter');
    const semesterFilter = document.getElementById('semester-filter');
    const unitFilter = document.getElementById('unit-filter');

    // Filtros para materias
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

    function checkDuplicateAssignment() {
        const teacherId = teacherSelect.value;
        const selectedSubject = document.querySelector('input[name="subject_id"]:checked');
        const period = periodSelect.value;

        if (teacherId && selectedSubject && period) {
            // Aquí podrías hacer una llamada AJAX para verificar si ya existe la asignación
            console.log('Verificando asignación duplicada...');
        }
    }

    teacherSelect.addEventListener('change', checkDuplicateAssignment);
    periodSelect.addEventListener('change', checkDuplicateAssignment);
    
    // Event listener para radio buttons de materias
    document.querySelectorAll('input[name="subject_id"]').forEach(radio => {
        radio.addEventListener('change', checkDuplicateAssignment);
    });
});
</script>

<style>
.subject-checkbox:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.subject-checkbox input[type="radio"]:checked + div {
    background-color: #eff6ff;
}

.subject-checkbox input[type="radio"]:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}
</style>
@endsection 