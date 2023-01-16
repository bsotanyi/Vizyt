<?php

class EventController {
    public static function list() {
        checkLogin();

        $events = DB::query("SELECT * FROM events WHERE `datetime` > CURRENT_TIMESTAMP AND user_id=:user_id", [
            'user_id' => $_SESSION['user']['id'],
        ]);
        $archive = DB::query("SELECT * FROM events WHERE `datetime` <= CURRENT_TIMESTAMP AND user_id=:user_id", [
            'user_id' => $_SESSION['user']['id'],
        ]);

        view('pages/events-list', [
            'title' => 'Events',
            'active_page' => 'events',
            'events' => $events,
            'archive' => $archive,
        ]);
    }

    public static function details() {
        $errors = [];
        
        if (!isset($_GET['id']) || !strlen(trim($_GET['id']))) {
            $errors[] = "The field 'id' is required";
        }

        $data = DB::fetchRow("SELECT u.firstname AS 'fname', u.lastname AS 'lname', e.* FROM events e INNER JOIN users u ON u.id = e.user_id WHERE u.id = :id", [ 'id' => $_GET['id'] ]);
        $comments = DB::query("SELECT u.firstname AS 'fname', u.lastname AS 'lname', c.comment AS 'comment', c.datetime AS 'datetime' FROM comments c INNER JOIN users u ON c.user_id = u.id WHERE c.event_id = :id", [ 'id' => $_GET['id'] ]);

        view('pages/event-details', [
            'title' => 'Details',
            'active_page' => 'details',
            'comments' => $comments,
            'data' => $data
        ]);
    }

    public static function edit() {
        checkLogin();

        if (empty($_GET['id'])) {
            header('Location: /events');
            exit;
        }

        if (is_numeric($_GET['id'])) {
            $event = DB::fetchRow("SELECT * FROM events WHERE id=:id", [
                'id' => $_GET['id'],
            ]);

            $event['invites'] = json_decode($event['invites'], true);
        }

        $templates = DB::fetchByKey("SELECT * FROM events WHERE is_template=1 AND user_id=:user_id", [
            'user_id' => $_SESSION['user']['id'],
        ], 'id');

        array_walk($templates, function(&$t) {
            $t['invites'] = json_decode($t['invites'], true);
            $t['invites'] = array_map(function($invite) {
                return join(',', $invite);
            }, $t['invites']);
            $t['invites'] = join(PHP_EOL, $t['invites']);
        });

        view('pages/event-edit', [
            'title' => 'Edit event',
            'active_page' => 'events',
            'model' => $event ?? [],
            'templates' => $templates,
        ]);
    }

    public static function save() {
        checkLogin();

        $errors = [];
        $required = [
            'name',
            'description',
            'invites',
            'datetime',
            'reminder',
        ];
        
        foreach ($required as $field) {
            if (!isset($_POST[$field]) || !strlen(trim($_POST[$field]))) {
                $errors[] = "The field '$field' is required";
            }
        }

        $_POST['datetime'] = date('Y-m-d H:i:s', strtotime($_POST['datetime']));
        $_POST['reminder'] = date('Y-m-d H:i:s', strtotime($_POST['reminder']));

        $invites = array_filter(explode(PHP_EOL, $_POST['invites']));
        $invites = array_filter($invites, function($item) {
            return trim($item) !== ',';
        });

        $invites = array_map(function($item) {
            $parts = explode(',', $item);
            return [
                'email' => trim($parts[0]),
                'name' => trim($parts[1] ?? ''),
            ];
        }, $invites);

        if (!empty($errors)) {
            $_SESSION['messages'] = $errors;
        } else {
            DB::insertOrUpdate('events', [
                'id' => is_numeric($_GET['id']) ? $_GET['id'] : null,
            ], [
                'user_id' => $_SESSION['user']['id'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'invites' => json_encode($invites),
                'is_active' => empty($_POST['is_active']) ? 0 : 1,
                'is_public' => empty($_POST['is_public']) ? 0 : 1,
                'is_commentable' => empty($_POST['is_commentable']) ? 0 : 1,
                'is_template' => empty($_POST['is_template']) ? 0 : 1,
                'longitude' => $_POST['longitude'],
                'latitude' => $_POST['latitude'],
                'datetime' => $_POST['datetime'],
                'reminder' => $_POST['reminder'],
            ]);
            $_SESSION['messages'] = ['Succesful update.'];
            $insert_id = DB::lastId();
            if ($insert_id === '0') $insert_id = $_GET['id'];

            // send invitations

            $existing_invites = DB::fetchByKey("SELECT * FROM invites WHERE event_id=:event_id", [
                'event_id' => $insert_id,
            ], 'receiver_email');
            foreach ($invites as $invite) {
                if (!isset($existing_invites[$invite['email']])) {
                    $token = md5(time() . mt_rand());

                    sendMail(
                        $invite['email'],
                        $invite['name'],
                        'Vizyt - You have a new event invitation',
                        'emails/email-invitation',
                        [
                            'token' => $token,
                            'invited_name' => $invite['name'],
                            'event_name' => $_POST['name'],
                            'username_from' => $_SESSION['user']['firstname'] . ' ' . $_SESSION['user']['lastname'],
                            'datetime' => $_POST['datetime'],
                        ],
                    );

                    DB::insertOrUpdate('invites', [
                        'event_id' => $insert_id,
                        'receiver_email' => $invite['email'],
                        'token' => $token,
                    ]);
                }
            }

        }

        header('Location: /events/edit/' . ($insert_id ?? 'new'));
    }

    public static function delete() {
        checkLogin();

        if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
            DB::query("DELETE FROM events WHERE id=:id AND user_id=:user_id LIMIT 1", [
                'id' => $_GET['id'],
                'user_id' => $_SESSION['user']['id'], // this is needed so the user doesn't delete events from other people
            ]);

            if (DB::$affected_rows) {
                $_SESSION['messages'] = ['Deleted successfully'];
            } else {
                $_SESSION['messages'] = ['You can not delete this item.'];
            }
        }

        header('Location: /events');
        exit;
    }

    public static function nearby () {
        $errors = [];
        $required = [
            'latitude',
            'longitude'
        ];
        unset($_SESSION['nearby']);

        foreach ($required as $field) {
            if (!isset($_GET[$field]) || !strlen(trim($_GET[$field]))) {
                $errors[] = "The field '$field' is required";
            }
        }

        if (empty($errors)) {
            $events = DB::query("SELECT id, latitude, longitude FROM events");
            
            foreach ($events as $item) {
                if (!empty($item['latitude']) && !empty($item['longitude'])) {
                    $distance = haversineGreatCircleDistance($_GET['latitude'], $_GET['longitude'], $item['latitude'], $item['longitude']) / 1000;

                    if ($distance <= 50) {
                        $data[] = DB::fetchRow("SELECT u.firstname AS 'fname', u.lastname AS 'lname', e.* FROM events e INNER JOIN users u ON e.user_id = u.id WHERE e.id=:id AND e.is_public = 1 AND e.is_active = 1 AND e.datetime > CURRENT_TIMESTAMP", [ 'id' => $item['id'] ]);
                    }
                }
            }

            foreach ($data as $item) {
                if (!empty($item))
                    $_SESSION['nearby'][] = $item;
            }   
        } else {
            $_SESSION['messages'] = $errors;
        }

        header('Location: /');
            die;
    }

    public static function comment() {
        checkLogin();

        $errors = [];
        $required = [
            'comment',
            'id'
        ];

        foreach ($required as $field) {
            if (!isset($_POST[$field]) || !strlen(trim($_POST[$field]))) {
                $errors[] = "The field '$field' is required";
            }
        }

        if (empty($errors)) {
            $start = DB::fetchValue("SELECT datetime FROM events WHERE id = :id", [ 'id' => $_POST['id']]);
            date_create($start);
            if ($start < NOW) {
                DB::query("INSERT INTO comments (event_id, user_id, comment, datetime) VALUES (:event, :user, :comment, CURRENT_TIMESTAMP)", [ 
                    'event' => $_POST['id'],
                    'user' => $_SESSION['user']['id'],
                    'comment' => $_POST['comment']        
                ]);
            } else {
                $_SESSION['messages'] = ["Comments can be posted only after the event."];
            }
        } else {
            $_SESSION['messages'] = $errors;
        }
        header('Location: /events/' . $_POST['id']);
        die;
    }
}