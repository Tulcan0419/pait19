@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="{{ asset('css/registro.estudiante.auth.css') }}" rel="stylesheet"> {{-- Puedes reutilizar este CSS o crear uno específico --}}

<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <i class="fas fa-chalkboard-teacher mr-2"></i>{{ __('Registro de Profesor') }}
        </div>

        <div class="register-body">
            <h2 class="register-title">Crea tu cuenta de profesor</h2>

            <form method="POST" action="{{ route('profesor.register.submit') }}">
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
                        <label for="teacher_code" class="form-label">{{ __('Código de Profesor (Opcional)') }}</label>
                        <input id="teacher_code" type="text" class="form-control @error('teacher_code') is-invalid @enderror"
                               name="teacher_code" value="{{ old('teacher_code') }}"
                               placeholder="Ej: PROFE001">
                        <i class="fas fa-id-card"></i>

                        @error('teacher_code')
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
                    <div class="col-md-6 offset-md-3">
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
                    ¿Ya tienes una cuenta? <a href="{{ route('profesor.login') }}">Inicia sesión aquí</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection