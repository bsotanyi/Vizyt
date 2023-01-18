<?php

class AdminController {
    public static function dashboard() {
        checkLogin();

        $dates = array_map(function($num) {
            return date('Y-m-d', strtotime($num . ' days'));
        }, range(-30, 0));

        $date_labels = array_map(function ($date) {
            return date('M d', strtotime($date));
        }, $dates);

        $page_views = DB::fetchKeyPair("SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as day, COUNT(*) FROM page_views WHERE created_at > :from GROUP BY day", [
            'from' => $dates[0],
        ]);
        $page_views = array_combine($date_labels, array_merge(array_fill_keys($dates, 0),$page_views));

        $page_views_device = DB::fetchKeyPair("SELECT device, COUNT(*) FROM page_views WHERE created_at > :from GROUP BY device", [
            'from' => $dates[0],
        ]);

        $page_views_country = DB::fetchKeyPair("SELECT country, COUNT(*) FROM page_views WHERE created_at > :from GROUP BY country", [
            'from' => $dates[0],
        ]);

        if (isset($page_views_country[''])) {
            $page_views_country['Unknown'] = $page_views_country[''];
            unset($page_views_country['']);
        }

        view('pages/admin-dashboard', [
            'title' => 'Admin',
            'active_page' => 'admin-dashboard',
            'page_views' => $page_views,
            'page_views_device' => $page_views_device,
            'page_views_country' => $page_views_country,
        ]);
    }

    public static function users() {      
        checkLogin();
  
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