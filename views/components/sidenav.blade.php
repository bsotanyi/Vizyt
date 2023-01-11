<aside id="sidenav">
    <div class="sticky">
        <a href="#">
            <strong>{{ $title }}</strong>
        </a>
        <div>
            <a href="{{ SITE_ROOT }}/" class="@if ($active_page == 'home') active @endif">
                <i icon-name="home"></i>Home
            </a>
            <a href="{{ SITE_ROOT }}/user/profile" class="@if ($active_page == 'profile') active @endif">
                <i icon-name="user"></i>Profile
            </a>
            <a href="{{ SITE_ROOT }}/table" class="@if ($active_page == 'table') active @endif">
                <i icon-name="table"></i>Table
            </a>
            <a href="{{ SITE_ROOT }}/events" class="@if ($active_page == 'events') active @endif">
                <i icon-name="calendar-days"></i>Events
            </a>
            <a href="{{ SITE_ROOT }}/admin/dashboard" class="@if ($active_page == 'admin-dashboard') active @endif">
                <i icon-name="gauge"></i>Dashboard
            </a>
            <a href="{{ SITE_ROOT }}/admin/users" class="@if ($active_page == 'admin-users') active @endif">
                <i icon-name="users"></i>Users
            </a>
        </div>
    </div>
</aside>