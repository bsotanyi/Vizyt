<?php

class HomeController {
    public static function home() {
        view('pages/home', [
            'title' => 'Home',
            'active_page' => 'home',
        ]);
    }

    public static function profile() {
        view('pages/profile', [
            'title' => 'Profile',
            'active_page' => 'profile',
        ]);
    }

    public static function table() {
        view('pages/table', [
            'title' => 'Table',
            'active_page' => 'table',
        ]);
    }
}