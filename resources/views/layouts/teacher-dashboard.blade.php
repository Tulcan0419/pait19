@extends('layouts.dashboard-base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboards/teacher.dashboard.css') }}">
@endpush

@section('user-name')
    {{ Auth::guard('teacher')->user()->name }}
@endsection

@section('user-role')
    Profesor
@endsection

@section('profile-photo-url')
    {{ \App\Http\Controllers\ProfilePhotoController::getProfilePhotoUrl(Auth::guard('teacher')->user()) }}
@endsection

@section('profile-photo-route')
    {{ route('profesor.profile.photo') }}
@endsection

@section('logout-route')
    {{ route('profesor.logout') }}
@endsection 