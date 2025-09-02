<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tecnológico Traversari - ISTPET') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Estilos específicos para el menú móvil */
        .mobile-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: white;
            border-top: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 50;
        }
        
        .mobile-menu.show {
            display: block;
        }
        
        .mobile-menu-button {
            transition: all 0.3s ease;
        }
        
        .mobile-menu-button.active {
            background-color: #f3f4f6;
            color: #374151;
        }
        
        /* Animación del icono del menú */
        .mobile-menu-button svg path.inline-flex {
            transition: transform 0.3s ease;
        }
        
        .mobile-menu-button.active svg path.inline-flex {
            transform: rotate(90deg);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navegación Principal -->
        <nav class="bg-white border-b border-gray-100 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ url('/') }}">
                                <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                            </a>
                        </div>

                        <!-- Enlaces de Navegación -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link href="{{ url('/') }}" :active="request()->is('/')">
                                Inicio
                            </x-nav-link>
                            
                            @auth('student')
                                <x-nav-link href="{{ route('estudiante.dashboard') }}" :active="request()->routeIs('estudiante.dashboard*')">
                                    Panel de Estudiante
                                </x-nav-link>
                            @endauth

                            @auth('teacher')
                                <x-nav-link href="{{ route('profesor.dashboard') }}" :active="request()->routeIs('profesor.dashboard*')">
                                    Panel de Profesor
                                </x-nav-link>
                            @endauth

                            @auth('admin')
                                <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard*')">
                                    Panel de Administrador
                                </x-nav-link>
                            @endauth

                            @auth('coordinador')
                                <x-nav-link href="{{ route('coordinador.dashboard') }}" :active="request()->routeIs('coordinador.dashboard*')">
                                    Panel de Coordinador
                                </x-nav-link>
                            @endauth
                        </div>
                    </div>

                    <!-- Menú de Usuario -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @guest
                            <!-- Enlaces de Acceso para Visitantes -->
                            <div class="flex space-x-4">
                                <x-nav-link href="{{ route('estudiante.login') }}" :active="request()->routeIs('estudiante.login')">
                                    Acceso Estudiante
                                </x-nav-link>
                                <x-nav-link href="{{ route('profesor.login') }}" :active="request()->routeIs('profesor.login')">
                                    Acceso Profesor
                                </x-nav-link>
                                <x-nav-link href="{{ route('admin.login') }}" :active="request()->routeIs('admin.login')">
                                    Acceso Administrador
                                </x-nav-link>
                                <x-nav-link href="{{ route('coordinador.login') }}" :active="request()->routeIs('coordinador.login')">
                                    Acceso Coordinador
                                </x-nav-link>
                            </div>
                        @else
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                        <div>
                                            @if(Auth::guard('student')->check())
                                                {{ Auth::guard('student')->user()->name }}
                                            @elseif(Auth::guard('teacher')->check())
                                                {{ Auth::guard('teacher')->user()->name }}
                                            @elseif(Auth::guard('admin')->check())
                                                {{ Auth::guard('admin')->user()->name }}
                                            @elseif(Auth::guard('coordinador')->check())
                                                {{ Auth::guard('coordinador')->user()->name }}
                                            @endif
                                        </div>

                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link href="#">
                                        Mi Perfil
                                    </x-dropdown-link>

                                    <!-- Formularios de Cerrar Sesión para cada guard -->
                                    @if(Auth::guard('student')->check())
                                        <form method="POST" action="{{ route('estudiante.logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('estudiante.logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                Cerrar Sesión
                                            </x-dropdown-link>
                                        </form>
                                    @elseif(Auth::guard('teacher')->check())
                                        <form method="POST" action="{{ route('profesor.logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('profesor.logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                Cerrar Sesión
                                            </x-dropdown-link>
                                        </form>
                                    @elseif(Auth::guard('admin')->check())
                                        <form method="POST" action="{{ route('admin.logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('admin.logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                Cerrar Sesión
                                            </x-dropdown-link>
                                        </form>
                                    @elseif(Auth::guard('coordinador')->check())
                                        <form method="POST" action="{{ route('coordinador.logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('coordinador.logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                Cerrar Sesión
                                            </x-dropdown-link>
                                        </form>
                                    @endif
                                </x-slot>
                            </x-dropdown>
                        @endguest
                    </div>

                    <!-- Botón de Menú Móvil -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button id="mobile-menu-button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" aria-label="Alternar navegación" aria-expanded="false">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path class="closed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Menú de Navegación Responsivo -->
            <div id="mobile-menu" class="mobile-menu sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link href="{{ url('/') }}" :active="request()->is('/')">
                        Inicio
                    </x-responsive-nav-link>
                    
                    @auth('student')
                        <x-responsive-nav-link href="{{ route('estudiante.dashboard') }}" :active="request()->routeIs('estudiante.dashboard*')">
                            Panel de Estudiante
                        </x-responsive-nav-link>
                    @endauth

                    @auth('teacher')
                        <x-responsive-nav-link href="{{ route('profesor.dashboard') }}" :active="request()->routeIs('profesor.dashboard*')">
                            Panel de Profesor
                        </x-responsive-nav-link>
                    @endauth

                    @auth('admin')
                        <x-responsive-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard*')">
                            Panel de Administrador
                        </x-responsive-nav-link>
                    @endauth

                    @auth('coordinador')
                        <x-responsive-nav-link href="{{ route('coordinador.dashboard') }}" :active="request()->routeIs('coordinador.dashboard*')">
                            Panel de Coordinador
                        </x-responsive-nav-link>
                    @endauth
                </div>

                <!-- Opciones de Usuario Responsivas -->
                @guest
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="space-y-1">
                            <x-responsive-nav-link href="{{ route('estudiante.login') }}" :active="request()->routeIs('estudiante.login')">
                                Acceso Estudiante
                            </x-responsive-nav-link>
                            <x-responsive-nav-link href="{{ route('profesor.login') }}" :active="request()->routeIs('profesor.login')">
                                Acceso Profesor
                            </x-responsive-nav-link>
                            <x-responsive-nav-link href="{{ route('admin.login') }}" :active="request()->routeIs('admin.login')">
                                Acceso Administrador
                            </x-responsive-nav-link>
                            <x-responsive-nav-link href="{{ route('coordinador.login') }}" :active="request()->routeIs('coordinador.login')">
                                Acceso Coordinador
                            </x-responsive-nav-link>
                        </div>
                    </div>
                @else
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                @if(Auth::guard('student')->check())
                                    {{ Auth::guard('student')->user()->name }}
                                @elseif(Auth::guard('teacher')->check())
                                    {{ Auth::guard('teacher')->user()->name }}
                                @elseif(Auth::guard('admin')->check())
                                    {{ Auth::guard('admin')->user()->name }}
                                @elseif(Auth::guard('coordinador')->check())
                                    {{ Auth::guard('coordinador')->user()->name }}
                                @endif
                            </div>
                            <div class="font-medium text-sm text-gray-500">
                                @if(Auth::guard('student')->check())
                                    Estudiante
                                @elseif(Auth::guard('teacher')->check())
                                    Profesor
                                @elseif(Auth::guard('admin')->check())
                                    Administrador
                                @elseif(Auth::guard('coordinador')->check())
                                    Coordinador
                                @endif
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link href="#">
                                Mi Perfil
                            </x-responsive-nav-link>

                            <!-- Formularios de Cerrar Sesión para cada guard -->
                            @if(Auth::guard('student')->check())
                                <form method="POST" action="{{ route('estudiante.logout') }}">
                                    @csrf
                                    <x-responsive-nav-link :href="route('estudiante.logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        Cerrar Sesión
                                    </x-responsive-nav-link>
                                </form>
                            @elseif(Auth::guard('teacher')->check())
                                <form method="POST" action="{{ route('profesor.logout') }}">
                                    @csrf
                                    <x-responsive-nav-link :href="route('profesor.logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        Cerrar Sesión
                                    </x-responsive-nav-link>
                                </form>
                            @elseif(Auth::guard('admin')->check())
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <x-responsive-nav-link :href="route('admin.logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        Cerrar Sesión
                                    </x-responsive-nav-link>
                                </form>
                            @elseif(Auth::guard('coordinador')->check())
                                <form method="POST" action="{{ route('coordinador.logout') }}">
                                    @csrf
                                    <x-responsive-nav-link :href="route('coordinador.logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        Cerrar Sesión
                                    </x-responsive-nav-link>
                                </form>
                            @endif
                        </div>
                    </div>
                @endguest
            </div>
        </nav>

        <!-- Contenido de la Página -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>