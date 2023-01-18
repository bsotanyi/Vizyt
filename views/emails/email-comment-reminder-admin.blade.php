@extends('email-layout')

@section('subject', $subject ?? 'test')

@section('content')
    <p>Dear {{ $invited_name }}!</p>
    <p>
        Your events (<strong>{{ $event_name }}</strong>) comment section is open by today.
    </p>
    <p><a href="{{ SITE_URL }}/events/{{ $event_id }}">Click on this link</a> to go to the event's page, and see what others think.</p>
@endsection