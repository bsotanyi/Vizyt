<aside id="sidenav">
    <div class="sticky">
        <a href="#">
            <strong>{{ $title }}</strong>
        </a>
        <div>
            <a href="/" class="@if ($active_page == 'home') active @endif">
                <i icon-name="home"></i>Home
            </a>
            <a href="/profile" class="@if ($active_page == 'profile') active @endif">
                <i icon-name="user"></i>Profile
            </a>
            <a href="/table" class="@if ($active_page == 'table') active @endif">
                <i icon-name="table"></i>Table
            </a>
        </div>
    </div>
</aside>