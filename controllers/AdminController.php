<?php

class AdminController {
    public static function dashboard() {
        view('pages/admin-dashboard', [
            'title' => 'Admin',
            'active_page' => 'admin-dashboard',
        ]);
    }

    public static function users() {
        view('pages/admin-users', [
            'title' => 'Users',
            'active_page' => 'admin-users',
        ]);
    }
}