{{-- login modal --}}
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="loginModalLabel">Log in</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form action="{{ SITE_ROOT }}/user/login" method="post" class="js-validate">
            <div class="modal-body">
                <label for="emailLoginModal">Email </label><input type="email" id="emailLoginModal" name="email" class="form-control">
                <label for="passwordLoginModal">Password </label><input type="password" id="passwordLoginModal" name="password" class="form-control">
                <a href="" data-bs-toggle="modal" data-bs-target="#forgotModal">Forgot Password?</a>
            </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="Log in">
        </div>
    </form>
    </div>
  </div>
</div>

{{-- register modal --}}
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="registerModal">Register</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form action="{{ SITE_ROOT }}/user/register" method="post" class="js-validate">
            <div class="modal-body row">

              <div class="form-group col-md-6">
                <label for="fnameModal">Firstname </label><input type="text" id="fnameModal" name="fname" class="form-control col-xs-2" required>
              </div>

              <div class="form-group col-md-6">
                <label for="lnameModal">Lastname </label><input type="text" id="lnameModal" name="lname" class="form-control" required>
              </div>

              <div class="form-group col-md-12">
                <label for="emailRegisterModal">Email </label><input type="email" id="emailRegisterModal" name="email" class="form-control" required>
              </div>

              <div class="form-group col-md-6">
                <label for="passwordRegisterModal">Password </label><input type="password" id="passwordRegisterModal" name="password" class="form-control" required>
              </div>

              <div class="form-group col-md-6">
                <label for="passwordConfirmModal">Password </label><input type="password" id="passwordConfirmModal" name="password_confirm" class="form-control" required data-pristine-equals="#passwordRegisterModal">
              </div>

                <a href="" data-bs-toggle="modal" data-bs-target="#loginModal">Already registered?</a>

            </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="Register">
        </div>
    </form>
    </div>
  </div>
</div>

{{-- forgot password --}}
<div class="modal fade" id="forgotModal" tabindex="-1" aria-labelledby="forgotModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="forgotModalLabel">Forgotten password</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form action="" method="post" class="js-validate">
            <div class="modal-body">
                <p>Enter the email address associated with your account and we'll send you a link to reset your password.</p>
                <label for="emailForgotModal">Email </label><input type="email" id="emailForgotModal" name="email" class="form-control">
            </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="Continue">
        </div>
    </form>
    </div>
  </div>
</div>

@if (!empty($messages))
    <div class="modal fade" id="messagesModal" tabindex="-1" aria-labelledby="messagesModal" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="forgotModalLabel">Forgotten password</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @foreach ($messages as $msg)
                <p>{{ $msg }}</p>
            @endforeach
        </div>
        </div>
    </div>
@endif