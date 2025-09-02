@props(['user', 'size' => 'md'])

@php
    $sizeClasses = [
        'sm' => 'w-8 h-8',
        'md' => 'w-12 h-12',
        'lg' => 'w-16 h-16',
        'xl' => 'w-24 h-24',
        '2xl' => 'w-32 h-32'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<img src="{{ \App\Http\Controllers\ProfilePhotoController::getProfilePhotoUrl($user) }}" 
     alt="Foto de perfil de {{ $user->name }}" 
     class="{{ $sizeClass }} rounded-full object-cover border-2 border-gray-200 shadow-sm {{ $attributes->get('class', '') }}"
     {{ $attributes->except('class') }}> 