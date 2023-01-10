<nav id="navigation">
    <i icon-name="menu" class="js-sidenav-toggle"></i>
    <div class="buttons float-end">
        @if (!empty($_SESSION['user']))
            <a href="#">{{ $_SESSION['user']['firstname'] .' '. $_SESSION['user']['lastname'] }}</a>
        @endif
        <a href="#">
            <i icon-name="search"></i>
        </a>
        <div class="dropdown">
            <i icon-name="user" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></i>
            <ul class="dropdown-menu dropdown-menu-dark">
                @if (empty($_SESSION['user']))
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Log in</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a></li>
                @else
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ SITE_ROOT }}/user/logout">Log out</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@include('pages/modals')