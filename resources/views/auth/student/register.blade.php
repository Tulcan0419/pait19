@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="{{ asset('css/registro.estudiante.auth.css') }}" rel="stylesheet">

<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <i class="fas fa-user-graduate mr-2"></i>{{ __('Registro de Estudiante') }}
        </div>

        <div class="register-body">
            <h2 class="register-title">Crea tu cuenta de estudiante</h2>

            <form method="POST" action="{{ route('estudiante.register.submit') }}">
                @csrf

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="name" class="form-label">{{ __('Nombre Completo') }}</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                               placeholder="Ingresa tu nombre completo">
                        <i class="fas fa-user"></i>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email"
                               placeholder="ejemplo@correo.com">
                        <i class="fas fa-envelope"></i>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="student_code" class="form-label">{{ __('Código de Estudiante') }}</label>
                        <input id="student_code" type="text" class="form-control @error('student_code') is-invalid @enderror"
                               name="student_code" value="{{ old('student_code') }}" required
                               placeholder="Ej: 20230001">
                        <i class="fas fa-id-card"></i>

                        @error('student_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="career" class="form-label">{{ __('Carrera') }}</label>
                        <select id="career" name="career" class="form-control @error('career') is-invalid @enderror" required>
                            <option value="">Selecciona tu carrera</option>
                            {{-- Las carreras se pasan desde el controlador StudentRegisterController --}}
                            @foreach($careers as $key => $value)
                                <option value="{{ $key }}" {{ old('career') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-graduation-cap"></i> {{-- Icono para la carrera --}}

                        @error('career')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="semester" class="form-label">{{ __('Semestre') }}</label>
                        <select id="semester" name="semester" class="form-control @error('semester') is-invalid @enderror" required>
                            <option value="">Selecciona tu semestre</option>
                            {{-- Los semestres se pasan desde el controlador StudentRegisterController --}}
                            @foreach($semesters as $key => $value)
                                <option value="{{ $key }}" {{ old('semester') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-calendar-alt"></i> {{-- Icono para el semestre --}}

                        @error('semester')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" required autocomplete="new-password"
                               placeholder="Mínimo 8 caracteres">
                        <i class="fas fa-lock"></i>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="password-confirm" class="form-label">{{ __('Confirmar Contraseña') }}</label>
                        <input id="password-confirm" type="password" class="form-control"
                               name="password_confirmation" required autocomplete="new-password"
                               placeholder="Repite tu contraseña">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-3">
                        <button type="submit" class="btn btn-register">
                            {{ __('Registrarse') }} <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <div class="login-link">
                    ¿Ya tienes una cuenta? <a href="{{ route('estudiante.login') }}">Inicia sesión aquí</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection