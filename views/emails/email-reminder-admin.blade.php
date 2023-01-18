@extends('email-layout')

@section('subject', $subject ?? 'test')

@section('content')
    <p>Dear {{ $invited_name }}!</p>
    <p>
        The reminder you have set for <strong>{{ $event_name }}</strong>, which will be held at {{ $datetime }}, was successfully sent out to all participants.
    </p>
    <p><a href="{{ SITE_URL }}/events/{{ $event_id }}">Click this link</a> to see the responses from all participants.</p>
@endsection