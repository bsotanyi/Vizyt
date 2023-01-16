<?php

class AdminController {
    public static function dashboard() {
        view('pages/admin-dashboard', [
            'title' => 'Admin',
            'active_page' => 'admin-dashboard',
        ]);
    }

    public static function users() {
        // $email = DB::query("SELECT JSON_EXTRACT(invites, '$.email') as 'email' FROM events WHERE JSON_EXTRACT(invites, '$.email') = :email", [ 'email' => $_SESSION['users']['email']]);
        //TODO idk
        
        $users = DB::query("SELECT * FROM users");

        view('pages/admin-users', [
            'title' => 'Users',
            'active_page' => 'admin-users',
            'users' => $users
        ]);
    }
}