<nav id="navigation">
    <i icon-name="menu" class="js-sidenav-toggle"></i>
    <div class="buttons float-end">
        <a href="#">
            <i icon-name="search"></i>
        </a>
        <div class="dropdown">
            <i icon-name="user" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></i>
            <ul class="dropdown-menu dropdown-menu-dark">
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Log in</a></li>
              <li><a class="dropdown-item" href="#">Register</a></li>
            </ul>
        </div>
    </div>
</nav>

@include('pages/login-modal')