@extends('email-layout')

@section('subject', $subject ?? 'test')

@section('content')
    <p>
        Thank you for your registration at Vizyt. Click on the link to confirm your registration:
        <a href='{{ SITE_URL . '/user/validate/' . $token }}'>Click!</a>
    </p>
@endsection