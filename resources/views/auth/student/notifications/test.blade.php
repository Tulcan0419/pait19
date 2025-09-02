@extends('layouts.student-dashboard')

@section('content')
<div style="padding: 20px; background: #f0f0f0;">
    <h1>Test Notifications Page</h1>
    <p>This is a test to verify the notifications page is working.</p>
    
    <div style="background: yellow; padding: 10px; margin: 10px;">
        <h3>Debug Info:</h3>
        <p>Student: {{ Auth::guard('student')->user() ? Auth::guard('student')->user()->name : 'Not authenticated' }}</p>
        <p>Notifications Count: {{ $notifications->count() }}</p>
        <p>Total: {{ $notifications->total() }}</p>
    </div>
    
    @if($notifications->count() > 0)
        <h2>Notifications Found:</h2>
        @foreach($notifications as $notification)
            <div style="background: white; padding: 10px; margin: 10px; border: 1px solid #ccc;">
                <h4>Notification ID: {{ $notification->id }}</h4>
                <p>Type: {{ $notification->type }}</p>
                <p>Read: {{ $notification->read_at ? 'Yes' : 'No' }}</p>
                <p>Created: {{ $notification->created_at }}</p>
                @if(isset($notification->data['message']))
                    <p>Message: {{ $notification->data['message'] }}</p>
                @endif
            </div>
        @endforeach
    @else
        <h2>No Notifications Found</h2>
        <p>This student has no notifications.</p>
    @endif
    
    <div style="margin-top: 20px;">
        <a href="{{ route('estudiante.dashboard') }}" style="background: blue; color: white; padding: 10px; text-decoration: none;">Back to Dashboard</a>
    </div>
</div>
@endsection 