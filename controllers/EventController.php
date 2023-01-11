<?php

class EventController {
    public static function list() {
        view('pages/events-list', [
            'title' => 'Events',
            'active_page' => 'events',
        ]);
    }

    public static function details() {
        view('pages/event-details', [
            'title' => 'Details',
            'active_page' => 'details',
        ]);
    }

    public static function edit() {

        if (empty($_GET['id'])) {
            header('Location: /events');
            exit;
        }

        if (is_numeric($_GET['id'])) {
            $event = DB::fetchRow("SELECT * FROM events WHERE id=:id", [
                'id' => $_GET['id'],
            ]);
        }

        view('pages/event-edit', [
            'title' => 'Edit event',
            'active_page' => 'events',
            'model' => $event ?? [],
        ]);
    }

    public static function comment() {
        $errors = [];
        if (!isset($_POST['comment']) && !strlen(trim($_POST['comment']))) {
            $errors[] = 'Empty comment!';
        }

        if (!empty($errors)) {
            $_SESSION['messages'] = $errors;
            header('Location: /events/details');
            die;
        } else {
            //TODO ADAM event_id and user_id needed
            // DB::query("INSERT INTO comments (event_id, user_id, comment, datetime) VALUES (:event, :user, :comment, :datetime)", [ 
            //     event => ,
            //     user => ,
            //     comment => $_POST['comment'],
            //     datetime => CURRENT_TIMESTAMP
        
            // ]);
            header('Location: /');
            die;
        }
    }
}