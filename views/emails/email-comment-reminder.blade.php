@extends('email-layout')

@section('subject', $subject ?? 'test')

@section('content')
    <p>Dear {{ $invited_name }}!</p>
    <p>
        We hope you had an amazing time at <strong>{{ $event_name }}</strong> yesterday.
    </p>
    <p>The comment section is now open, so you can share your best memories, or provide feedback for the host.</p>
    <p><a href="{{ SITE_URL }}/events/{{ $event_id }}">Click on this link</a> to go to the event's page.</p>
@endsection