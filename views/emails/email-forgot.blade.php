@extends('email-layout')

@section('subject', $subject ?? 'test')

@section('content')
    <p>
        Your can create a new password by clicking
        <a href='{{ SITE_URL . '/user/reset-password/' . $token . '/' . $email }}'>Here!</a>
    </p>
@endsection