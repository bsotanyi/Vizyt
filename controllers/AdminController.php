<?php

class AdminController {
    public static function dashboard() {
        view('pages/admin-dashboard', [
            'title' => 'Admin',
            'active_page' => 'admin-dashboard',
        ]);
    }
}