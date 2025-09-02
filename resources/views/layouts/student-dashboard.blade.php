@extends('layouts.dashboard-base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboards/student.dashboard.css') }}">
@endpush

@section('user-name')
    {{ Auth::guard('student')->user()->name }}
@endsection

@section('user-role')
    Estudiante
@endsection

@section('profile-photo-url')
    {{ \App\Http\Controllers\ProfilePhotoController::getProfilePhotoUrl(Auth::guard('student')->user()) }}
@endsection

@section('profile-photo-route')
    {{ route('estudiante.profile.photo') }}
@endsection

@section('logout-route')
    {{ route('estudiante.logout') }}
@endsection 