@extends('email-layout')

@section('subject', $subject ?? 'test')

@section('content')
    <p>Dear {{ $invited_name }}!</p>
    <p>
        You have an upcoming event, <strong>{{ $event_name }}</strong> at {{ $datetime }}.
    </p>
    <p>Don't forget to bring the item you selected from the wishlist!</p>
    <p><a href="{{ SITE_URL }}/events/invite-link/{{ $token }}">Click on this link</a> to see further details, and to tell us, whether you can attend or not (if you haven't already).</p>
@endsection