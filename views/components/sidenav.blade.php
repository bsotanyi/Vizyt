<aside id="sidenav">
    <div class="sticky">
        <a href="#">
            <strong>{{ $title }}</strong>
        </a>
        <div>
            <a href="/" class="@if ($active_page == 'home') active @endif">
                <i icon-name="home"></i>Home
            </a>
            <a href="/user/profile" class="@if ($active_page == 'profile') active @endif" @if(empty($_SESSION['user']))  data-bs-toggle="tooltip" data-bs-title="Please log in to see and edit your profile" onclick="return false" @endif>
                <i icon-name="user"></i>Profile
            </a>
            <a href="/events" class="@if ($active_page == 'events') active @endif" @if(empty($_SESSION['user']))  data-bs-toggle="tooltip" data-bs-title="Please log in to manage your events" onclick="return false" @endif>
                <i icon-name="calendar-days"></i>My events
            </a>
            @if (!empty($_SESSION['user']) && $_SESSION['user']['status'] === 'admin')
                <a href="/admin/dashboard" class="@if ($active_page == 'admin-dashboard') active @endif">
                    <i icon-name="gauge"></i>Dashboard
                </a>
                <a href="/admin/users" class="@if ($active_page == 'admin-users') active @endif">
                    <i icon-name="users"></i>Users
                </a>
            @endif
        </div>
    </div>
</aside>