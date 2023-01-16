@extends('email-layout')

@section('subject', $subject ?? 'test')

@section('content')
    <p>Dear {{ $invited_name }}!</p>
    <p>
        {{ $username_from }} invited you to their event, {{ $event_name }}, which will be held at {{ $datetime }}.
    </p>
    <p><a href="/events/invite-link/{{ $token }}">Click on this link</a> to see further details, and to tell us, whether you can attend or not.</p>
@endsection