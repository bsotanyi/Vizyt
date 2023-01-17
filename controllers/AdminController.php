<?php

use FontLib\Table\Type\head;

class AdminController {
    public static function dashboard() {
        view('pages/admin-dashboard', [
            'title' => 'Admin',
            'active_page' => 'admin-dashboard',
        ]);
    }

    public static function users() {        
        $users = DB::query("SELECT * FROM users");
        $created_count = DB::fetchKeyPair("SELECT u.id, COUNT(e.user_id) AS 'created' FROM events e INNER JOIN users u 
        ON u.id = e.user_id GROUP BY u.id");
        $invitesJson = DB::query("SELECT invites FROM events");
        $data = [];
        // $data = $invitesJson ? json_decode($invitesJson, true) : '';

        $attended_count = DB::fetchKeyPair("SELECT u.id, COUNT(i.id) FROM users u LEFT JOIN invites i ON i.receiver_email = u.email GROUP BY u.id");

        foreach ($invitesJson as $item) {
            if (!empty($item['invites']))
                array_push($data, json_decode($item['invites'] ?? '[]'));
        }
        

        // dd($data);

        view('pages/admin-users', [
            'title' => 'Users',
            'active_page' => 'admin-users',
            'users' => $users,
            'created_count' => $created_count,
            'attended_count' => $attended_count,
        ]);
    }

    public static function updateStatus() {
        $user_id = $_GET['id'];
        $active = $_GET['active'];
        DB::insertOrUpdate('users', [
            'id' => $user_id,
        ], [
            'active' => $active,
        ]);
        
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}