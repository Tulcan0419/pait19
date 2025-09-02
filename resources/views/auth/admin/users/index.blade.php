@extends('layouts.admin-dashboard')

@section('title', 'Gestión de Usuarios - Admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">
<div class="admin-users-container">
    <div class="admin-users-header">
        <h1>Gestión de Usuarios</h1>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.dashboard') }}" class="btn-corporate-secondary">Volver al Dashboard</a>
            <a href="{{ route('admin.users.create') }}" class="btn-corporate-primary">Crear Usuario</a>
        </div>
    </div>
    <form method="GET" action="{{ route('admin.users.index') }}" class="admin-users-filter">
        <label for="type">Filtrar por tipo:</label>
        <select name="type" id="type" onchange="this.form.submit()">
            <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Todos</option>
            <option value="admin" {{ $type == 'admin' ? 'selected' : '' }}>Administradores</option>
            <option value="student" {{ $type == 'student' ? 'selected' : '' }}>Estudiantes</option>
            <option value="teacher" {{ $type == 'teacher' ? 'selected' : '' }}>Profesores</option>
            <option value="coordinator" {{ $type == 'coordinator' ? 'selected' : '' }}>Coordinadores</option>
        </select>
    </form>
    <div class="admin-users-cards">
        @if($type == 'all' || $type == 'admin')
            @foreach($admins as $admin)
                <div class="user-card admin">
                    <div class="user-avatar"><i class="fas fa-user-shield"></i></div>
                    <div class="user-info">
                        <h3>{{ $admin->name }}</h3>
                        <p class="user-email">{{ $admin->email }}</p>
                        <span class="user-type">Administrador</span>
                    </div>
                    <div class="user-actions">
                        <a href="{{ route('admin.users.edit', ['type'=>'admin','id'=>$admin->id]) }}" class="btn-corporate-edit">Editar</a>
                        <form action="{{ route('admin.users.destroy', ['type'=>'admin','id'=>$admin->id]) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-corporate-delete" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
        @if($type == 'all' || $type == 'student')
            @foreach($students as $student)
                <div class="user-card student">
                    <div class="user-avatar"><i class="fas fa-user-graduate"></i></div>
                    <div class="user-info">
                        <h3>{{ $student->name }}</h3>
                        <p class="user-email">{{ $student->email }}</p>
                        <span class="user-type">Estudiante</span>
                        <span class="user-code">Código: {{ $student->student_code }}</span>
                        <span class="user-career">Carrera: {{ $student->career }}</span>
                    </div>
                    <div class="user-actions">
                        <a href="{{ route('admin.users.edit', ['type'=>'student','id'=>$student->id]) }}" class="btn-corporate-edit">Editar</a>
                        <form action="{{ route('admin.users.destroy', ['type'=>'student','id'=>$student->id]) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-corporate-delete" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
        @if($type == 'all' || $type == 'teacher')
            @foreach($teachers as $teacher)
                <div class="user-card teacher">
                    <div class="user-avatar"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div class="user-info">
                        <h3>{{ $teacher->name }}</h3>
                        <p class="user-email">{{ $teacher->email }}</p>
                        <span class="user-type">Profesor</span>
                        <span class="user-code">Código: {{ $teacher->teacher_code }}</span>
                        @if($teacher->subjects && $teacher->subjects->count())
                            <div class="mt-2">
                                <strong>Materias:</strong>
                                <ul class="list-unstyled mb-0">
                                    @foreach($teacher->subjects as $subject)
                                        <li><span class="badge bg-primary">{{ $subject->name }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                        @endif
                    </div>
                    <div class="user-actions">
                        <a href="{{ route('admin.users.edit', ['type'=>'teacher','id'=>$teacher->id]) }}" class="btn-corporate-edit">Editar</a>
                        <form action="{{ route('admin.users.destroy', ['type'=>'teacher','id'=>$teacher->id]) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-corporate-delete" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
        @if($type == 'all' || $type == 'coordinator')
            @foreach($coordinators as $coordinator)
                <div class="user-card coordinator">
                    <div class="user-avatar"><i class="fas fa-user-tie"></i></div>
                    <div class="user-info">
                        <h3>{{ $coordinator->name }}</h3>
                        <p class="user-email">{{ $coordinator->email }}</p>
                        <span class="user-type">Coordinador</span>
                    </div>
                    <div class="user-actions">
                        <a href="{{ route('admin.users.edit', ['type'=>'coordinator','id'=>$coordinator->id]) }}" class="btn-corporate-edit">Editar</a>
                        <form action="{{ route('admin.users.destroy', ['type'=>'coordinator','id'=>$coordinator->id]) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-corporate-delete" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection 