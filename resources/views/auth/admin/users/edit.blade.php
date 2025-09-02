@extends('layouts.admin-dashboard')

@section('title', 'Editar Usuario - Admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/users.edit.css') }}">
<div class="admin-users-container">
    <div class="admin-users-header">
        <h1>Editar Usuario</h1>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.users.index') }}" class="btn-corporate-secondary">Volver a Usuarios</a>
        </div>
    </div>
    <div class="admin-users-cards" style="justify-content:center;">
        <div class="user-card {{ $type }}" style="min-width:350px;max-width:500px;width:100%;">
            <form action="{{ route('admin.users.update', ['type'=>$type, 'id'=>$user->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                @if($type == 'student')
                <div class="mb-3">
                    <label for="student_code" class="form-label">Código de Estudiante</label>
                    <input type="text" name="student_code" id="student_code" class="form-control" value="{{ $user->student_code }}">
                </div>
                <div class="mb-3">
                    <label for="career" class="form-label">Carrera</label>
                    <select name="career" id="career" class="form-control">
                        <option value="mechanical" {{ $user->career == 'mechanical' ? 'selected' : '' }}>Mecánica</option>
                        <option value="software" {{ $user->career == 'software' ? 'selected' : '' }}>Desarrollo de Software</option>
                        <option value="education" {{ $user->career == 'education' ? 'selected' : '' }}>Educación Básica</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="semester" class="form-label">Semestre</label>
                    <select name="semester" id="semester" class="form-control">
                        <option value="1" {{ $user->semester == 1 ? 'selected' : '' }}>Primer Semestre</option>
                        <option value="2" {{ $user->semester == 2 ? 'selected' : '' }}>Segundo Semestre</option>
                        <option value="3" {{ $user->semester == 3 ? 'selected' : '' }}>Tercer Semestre</option>
                        <option value="4" {{ $user->semester == 4 ? 'selected' : '' }}>Cuarto Semestre</option>
                    </select>
                </div>
                @endif
                @if($type == 'teacher')
                <div class="mb-3">
                    <label for="teacher_code" class="form-label">Código de Profesor</label>
                    <input type="text" name="teacher_code" id="teacher_code" class="form-control" value="{{ $user->teacher_code }}">
                </div>
                
                @endif
                <div class="mb-3">
                    <label for="password" class="form-label">Nueva Contraseña (opcional)</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn-corporate-edit">Actualizar</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-corporate-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateSelectedSubjects() {
        const select = document.getElementById('subjects');
        const container = document.getElementById('selected-subjects');
        container.innerHTML = '';
        Array.from(select.selectedOptions).forEach(option => {
            const chip = document.createElement('span');
            chip.className = 'badge bg-primary me-1 mb-1';
            chip.textContent = option.text;
            container.appendChild(chip);
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('subjects');
        if (select) {
            select.addEventListener('change', updateSelectedSubjects);
            updateSelectedSubjects();
        }
    });
</script>
@endsection 