@extends('layouts.dashboard-base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboards/admin.dashboard.css') }}">
@endpush

@section('user-name')
    {{ Auth::guard('admin')->user()->name }}
@endsection

@section('user-role')
    Administrador
@endsection

@section('profile-photo-url')
    {{ \App\Http\Controllers\ProfilePhotoController::getProfilePhotoUrl(Auth::guard('admin')->user()) }}
@endsection

@section('profile-photo-route')
    {{ route('admin.profile.photo') }}
@endsection

@section('logout-route')
    {{ route('admin.logout') }}
@endsection 