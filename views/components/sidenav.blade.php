<aside id="sidenav">
    <div class="sticky">
        <a href="#">
            <strong>{{ $title }}</strong>
        </a>
        <div>
            <a href="/" class="@if ($active_page == 'home') active @endif">
                <i icon-name="home"></i>Home
            </a>
            <a href="/user/profile" class="@if ($active_page == 'profile') active @endif">
                <i icon-name="user"></i>Profile
            </a>
            <a href="/table" class="@if ($active_page == 'table') active @endif">
                <i icon-name="table"></i>Table
            </a>
            <a href="/events" class="@if ($active_page == 'events') active @endif">
                <i icon-name="calendar-days"></i>Events
            </a>
            <a href="/admin/dashboard" class="@if ($active_page == 'admin-dashboard') active @endif">
                <i icon-name="gauge"></i>Dashboard
            </a>
            <a href="/admin/users" class="@if ($active_page == 'admin-users') active @endif">
                <i icon-name="users"></i>Users
            </a>
        </div>
    </div>
</aside>