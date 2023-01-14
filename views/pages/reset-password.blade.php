@extends('layout')

@section('title', $title)

@section('content')
    <div class="parent grid-xxl">
        <div>
            <form action="/user/forgot-password" method="post" id="resetPasswordForm" class="js-validate">
                <label for="password" class="form-label">Password </label>
                <input type="password" name="password" id="password123" class="form-control" required data-pristine-minlength="6">

                <label for="passwordConfirm" class="form-label">Password Confirm </label>
                <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control" required data-pristine-equals="#password123">

                <input type="hidden" name="email" value="{{ $_GET['email'] }}">
                <input type="submit" value="Reset Password" class="btn btn-primary">
            </form>
        </div>
    </div>
@endsection