@extends('layouts.dashboard-base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboards/coordinator.dashboard.css') }}">
@endpush

@section('user-name')
    {{ Auth::guard('coordinador')->user()->name }}
@endsection

@section('user-role')
    Coordinador
@endsection

@section('profile-photo-url')
    {{ \App\Http\Controllers\ProfilePhotoController::getProfilePhotoUrl(Auth::guard('coordinador')->user()) }}
@endsection

@section('profile-photo-route')
    {{ route('coordinador.profile.photo') }}
@endsection

@section('logout-route')
    {{ route('coordinador.logout') }}
@endsection 