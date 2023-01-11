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
            sendMail(
                $_POST['email'],
                $_POST['fname'] . $_POST['lname'],
                'Adminers - Registration',
                'emails/email-confirmation',
                [
                    'token' => $token
                ],
            );
        }

        header('Location: /');
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
        header('Location: /');
        exit;
    }

    public static function logout() {
        unset($_SESSION['user']);
        $_SESSION['messages'] = ['Logged out succesfully'];
        header('Location: /');
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

        header('Location: /');
        die;
    }

    public static function forgotPasswordMail() {
        $user = DB::fetchRow("SELECT firstname, lastname FROM users WHERE email=:email", [
            'email' => $_POST['email']
        ]);

        $token = md5(time());
        $timestamp = time()+30*60;
        $expiresObj = new DateTime('@' . $timestamp);
        $expires = (array) $expiresObj;

        DB::insertOrUpdate('password_tokens', [
            'email' => $_POST['email'],
            'token' => $token,
            'expires' => $expires['date']
        ]);

        $result = sendMail(
            $_POST['email'],
            $user['firstname'] . $user['lastname'],
            'Adminers - Password Reset',
            'emails/email-forgot',
            [
                'token' => $token,
                'email' => $_POST['email']
            ],
        );

        if ($result) {
            $_SESSION['messages'] = ['Email sent!'];
        } else {
            $_SESSION['messages'] = ['Failed to send email!'];
        }
        header('Location: /');
        die;
    }

    public static function resetPassword() {
        if (strlen(trim($_GET['token']))) {
            $token_exists = DB::fetchValue("SELECT COUNT(*) FROM password_tokens WHERE token=:token", [
                'token' => $_GET['token'],
            ]);
        }

        if ($token_exists) {
            DB::query("UPDATE password_tokens SET token = '', expires = '' WHERE token = '{$_GET['token']}'");
            view('pages/reset-password', [
                'title' => 'Reset Password',
                'active_page' => 'Reset Password',
            ]);
        } else {
            $_SESSION['messages'] = ['Invalid token!'];
            header('Location: /');
            die;
        }
    }

    public static function forgotPassword() {
        $errors = [];
        $required = [
            'email',
            'password',
            'passwordConfirm'
        ];

        foreach ($required as $field) {
            if (!isset($_POST[$field]) && !strlen(trim($_POST[$field]))) {
                $errors[] = "The field '$field' is required";
            }
        }

        if ($_POST['password'] !== $_POST['passwordConfirm']) {
            $errors[] = "The passwords do not match!";
        }

        if (empty($errors)) {
            DB::query("UPDATE users SET password = :password WHERE email = :email", 
            [
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                'email' => $_POST['email']
            ]);
            $_SESSION['messages'] = ['Your password have been changed successfully!'];
        } else {
            $_SESSION['messages'] = $errors;
        }
        header('Location: /');
        die;
    }

    public static function profile() {
        if (empty($_SESSION['user'])) {
            $_SESSION['messages'] = ['You are not logged in'];
            header('Location: /');
            exit;
        }

        view('pages/profile', [
            'title' => 'Profile',
            'active_page' => 'profile',
            'user' => $_SESSION['user'],
        ]);
    }

    public static function save() {
        $errors = [];
        $required = [
            'firstname',
            'lastname',
            'email',
        ];

        foreach ($required as $field) {
            if (!isset($_POST[$field]) && !strlen(trim($_POST[$field]))) {
                $errors[] = "The field '$field' is required";
            }
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Incorrect email format.';
        }

        $user = DB::fetchValue("SELECT COUNT(*) FROM users WHERE email=:email AND id <> :id", [
            'email' => $_POST['email'],
            'id' => $_SESSION['user']['id'],
        ]);

        if (!empty($user)) {
            $errors[] = 'This email address is already taken by another user.';
        }

        if ($_POST['password'] !== $_POST['password_again']) {
            $errors[] = 'Passwords do not match.';
        }

        if (empty($errors)) {
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $_SESSION['user']['password'];
            $wishlist = [];
            foreach ($_POST['wishlist_items'] ?? [] as $key => $name) {
                if (empty($name)) continue;
                $wishlist[] = [
                    'name' => $name,
                    'url' => $_POST['wishlist_urls'][$key] ?? '',
                ];
            }
            $wishlist = json_encode($wishlist);

            DB::insertOrUpdate('users',
                [
                    'id' => $_SESSION['user']['id'],
                ],
                [
                    'firstname' => $_POST['firstname'],
                    'lastname' => $_POST['lastname'],
                    'email' => $_POST['email'],
                    'password' => $password,
                    'wishlist' => $wishlist,
                ],
            );

            // also reset user data in session
            $user = DB::fetchRow("SELECT * FROM users WHERE id=:id", [
                'id' => $_SESSION['user']['id'],
            ]);
            $_SESSION['user'] = $user;
            $_SESSION['messages'] = ['Successful update!'];
        } else {
            $_SESSION['messages'] = $errors;
        }

        header('Location: /user/profile');
        exit;
    }
}