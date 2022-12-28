<aside id="sidenav">
    <div class="sticky">
        <a href="#">
            <strong>{{ $title }}</strong>
        </a>
        <div>
            <a href="/" class="{{ ($active_page ?? '') == 'home' ? 'active' : '' }}">
                <i icon-name="home"></i>Home
            </a>
            <a href="/profile" class="{{ ($active_page ?? '') == 'profile' ? 'active' : '' }}">
                <i icon-name="user"></i>Profile
            </a>
            <a href="/table" class="{{ ($active_page ?? '') == 'table' ? 'active' : '' }}">
                <i icon-name="table"></i>Table
            </a>
        </div>
    </div>
</aside>