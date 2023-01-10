<?php

class HomeController {
    public static function home() {
        $messages = $_SESSION['messages'] ?? [];
        unset($_SESSION['messages']);

        view('pages/home', [
            'title' => 'Home',
            'active_page' => 'home',
            'messages' => $messages,
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