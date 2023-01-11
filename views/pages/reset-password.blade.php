@extends('layout')

@section('title', $title)

@section('content')
    <div class="parent grid">
        <div>
            <form action="/user/forgot-password" method="post" id="resetPasswordForm">
                <input type="password" name="password" id="password">
                <input type="password" name="passwordConfirm" id="passwordConfirm">
                <input type="hidden" name="email" value="{{ $_GET['email'] }}">

                <input type="submit" value="Reset Password">
            </form>
        </div>
    </div>
@endsection