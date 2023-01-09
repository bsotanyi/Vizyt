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
                'password' => password_hash($_POST['fname'], PASSWORD_BCRYPT),
                'token' => $token,
            ]);
            $_SESSION['messages'] = [
                'User created successfully. We have sent you an email with an activation link.',
            ];
            //TODO send activation email link
        }

        header('Location: /');
        exit;
    }

    public static function login() {
        
    }
}