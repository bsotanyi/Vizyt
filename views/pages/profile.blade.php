@extends('layout')

@section('title', $title)

@section('content')
    <div class="parent grid">
        <div>
            <form action="" method="post" class="js-validate">
                <small>View & Edit</small>
                <h3>User profile</h3>
                <hr>
                <div class="grid-md-fill">
                    <div class="form-group">
                        <label class="form-label" for="firstname">First name *</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="John" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="lastname">Last name *</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Doe" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">E-mail address *</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="user@example.org" required>
                    </div>
                    {{-- <div class="form-group">
                        <label class="form-label">Yes or no</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
                            <label class="form-check-label" for="flexCheckDefault">
                            Default checkbox
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" cols="30" rows="3" placeholder="Lorem ipsum..."></textarea>
                    </div> --}}
                </div>
                <div class="grid-2 mt-3">
                    <div>
                        <div class="form-group">
                            <label class="form-label" for="password">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                        </div>
                        <div class="form-group mt-2">
                            <label class="form-label" for="password_again">Password again *</label>
                            <input type="password" class="form-control" id="password_again" name="password_again" required data-pristine-equals="#password">
                        </div>
                    </div>
                    <div class="box">
                        <div class="js-repeatable">
                            <button type="button" class="btn btn-primary float-end js-repeatable-add">
                                <i icon-name="plus"></i>
                                Add
                            </button>
                            <h3 class="mb-3">Wishlist</h3>
                            <script type="text/template">
                                <div class="grid-3 mt-2 js-repeatable-row">
                                    <input type="text" class="form-control" placeholder="Item name">
                                    <input type="text" class="form-control" placeholder="Url (optional)">
                                    <button type="button" class="btn btn-danger square js-repeatable-remove">
                                        <i icon-name="x"></i>
                                    </button>
                                </div>
                            </script>
                            @for ($i = 0; $i < 3; $i++)
                                <div class="grid-3 mt-2 js-repeatable-row">
                                    <input type="text" class="form-control" placeholder="Item name">
                                    <input type="text" class="form-control" placeholder="Url (optional)">
                                    <button type="button" class="btn btn-danger square js-repeatable-remove">
                                        <i icon-name="x"></i>
                                    </button>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
                <br>
                <button class="btn bg-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection