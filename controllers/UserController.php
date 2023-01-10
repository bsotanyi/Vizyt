<?php

class UserController {
    public static function register() {
        $errors = [];
        $required = [
            'fname',
            'lname',
            'email',
            'password',
            'password_confirm',
        ];

        foreach ($required as $field) {
            if (!isset($_POST[$field]) && !strlen(trim($_POST[$field]))) {
                $errors[] = "The field '$field' is required";
            }
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Incorrect email format.';
        }

        if ($_POST['password_confirm'] !== $_POST['password']) {
            $errors[] = 'The provided passwords do not match.';
        }

        $email_exists = DB::fetchValue("SELECT COUNT(*) FROM users WHERE email=:email", [
            'email' => $_POST['email'],
        ]);

        if (!empty($email_exists)) {
            $errors[] = 'This email address is already taken. Please log in.';
        }

        if (!empty($errors)) {
            $_SESSION['messages'] = $errors;
        } else {
            $token = md5(time());
            DB::query("INSERT INTO users SET firstname=:firstname, lastname=:lastname, email=:email, password=:password, token=:token", [
                'firstname' => $_POST['fname'],
                'lastname' => $_POST['lname'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                'token' => $token,
            ]);
            $_SESSION['messages'] = [
                'User created successfully. We have sent you an email with an activation link.',
            ];
            sendMail($_POST['email'], $_POST['fname'] . $_POST['lname']);
        }

        header('Location: '.SITE_PATH);
        exit;
    }

    public static function login() {
        $errors = [];
        $required = [
            'email',
            'password'
        ];

        foreach ($required as $field) {
            if (!isset($_POST[$field]) && !strlen(trim($_POST[$field]))) {
                $errors[] = "The field '$field' is required";
            }
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Incorrect email format.';
        }

        $user = DB::fetchRow("SELECT * FROM users WHERE email=:email", [
            'email' => $_POST['email'],
        ]);

        if (empty($user)) {
            $errors[] = 'This email address is not yet registered.';
        } else {
            if (password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user'] = $user;
                $errors[] = 'Logged in successfully.';
            } else {
                $errors[] = 'Incorrect password';
            }
        }

        $_SESSION['messages'] = $errors;
        header('Location: '.SITE_PATH);
        exit;
    }

    public static function logout() {
        unset($_SESSION['user']);
        $_SESSION['messages'] = ['Logged out succesfully'];
        header('Location: '.SITE_PATH);
        exit;
    }

    public static function validateToken() {
        $errors = [];
        if (strlen(trim($_GET['token']))) {
            $token_exists = DB::fetchValue("SELECT COUNT(*) FROM users WHERE token=:token", [
                'token' => $_GET['token'],
            ]);
            if ($token_exists) {
                DB::query("UPDATE users SET token='', active=1 WHERE token=:token", [
                    'token' => $_GET['token'],
                ]);
                $_SESSION['messages'] = ['Email address confirmed successfully.',];
            } else {
                $errors[] = "Failed to confirm email address! (This token doesn't exist.)";
            }
        } else {
            $errors[] = "Failed to confirm email address! (No token given.)";
        }

        if (!empty($errors)) {
            $_SESSION['messages'] = $errors;
        }

        header("Location: ".SITE_PATH);
        die;
    }
}