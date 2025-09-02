<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test CSRF Token</title>
</head>
<body>
    <h1>Test CSRF Token</h1>
    
    <p>Token CSRF: <strong>{{ csrf_token() }}</strong></p>
    
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit">Test Logout</button>
    </form>
    
    <hr>
    
    <h2>Debug Info</h2>
    <p>Session ID: {{ session()->getId() }}</p>
    <p>Session Status: {{ session()->isStarted() ? 'Started' : 'Not Started' }}</p>
    <p>User Authenticated: {{ Auth::guard('admin')->check() ? 'Yes' : 'No' }}</p>
    
    @if(Auth::guard('admin')->check())
        <p>Admin User: {{ Auth::guard('admin')->user()->name }}</p>
    @endif
</body>
</html>
