<?php

declare(strict_types=1);    

class ApiController {
    public static function api () {
        require_once 'config.php';
        header('Content-type: application/json; CHARSET=UTF-8');

        $parts = explode("/", $_SERVER['REQUEST_URI']);

        if ($parts[1] !== 'api') {
            http_response_code(404);
            die;
        }

        if ($parts[2] !== 'users') {
            http_response_code(404);
            die;
        }

        $id = $parts[3] ?? '';

        $controller = new ApiController;
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $id);

    }

    public function processRequest (string $method, ?string $id): void {
        if ($id) {
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }

    private function processResourceRequest (string $method, string $id):void {
        $user = DB::fetchRow("SELECT * FROM users WHERE id = :id", [ 'id' => $id ]);

        if (empty($user)) {
            http_response_code(404);
            echo json_encode(["message" => "User not found!"]);
            return;
        }

        switch ($method) {
            case "GET":
                echo json_encode($user);
                break;

            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $password = $data['password'] ?? '';
                if (!empty($password)) {
                    $password = password_hash($data['password'], PASSWORD_BCRYPT);
                } else {
                    $password = $user['password'];
                }

                DB::query("UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, password = :password WHERE id = :id", [
                    'firstname' => $data['firstname'] ?? $user['firstname'],
                    'lastname' => $data['lastname'] ?? $user['lastname'],
                    'email' => $data['email'] ?? $user['email'],
                    'password' =>  $password,
                    'id' => $user['id']
                ]);

                if (DB::$affected_rows > 0) {
                    echo json_encode([
                        "message" => "User $id updated!",
                        "rows" => DB::$affected_rows
                    ]);
                } else {
                    http_response_code(422);
                    echo json_encode([
                        "message" => "Failed to update user!"
                    ]);
                }

            case "DELETE":
                DB::query("DELETE FROM users WHERE id = :id", [ 'id' => $id ]);

                if (DB::$affected_rows > 0) {
                    echo json_encode([
                        "message" => "User $id deleted!",
                        "rows" => DB::$affected_rows
                    ]);
                } else {
                    http_response_code(422);
                    echo json_encode([
                        "message" => "Failed to delete user!"
                    ]);
                }

                break;

            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE"); 
        }

    }

    private function processCollectionRequest (string $method):void {
        switch ($method) {
            case "GET":
                $items = DB::query("SELECT * FROM users");
                echo json_encode($items);
                break;

            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                if (empty($data['firstname']) || empty($data['lastname']) || empty($data['email']) || empty($data['password'])) {
                    http_response_code(400);
                    echo json_encode(["message" => "Missing parameter."]);
                    return;
                }

                DB::query("INSERT INTO users (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)", [
                    'firstname' => $data['firstname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => password_hash($data['password'], PASSWORD_BCRYPT)
                ]);

                http_response_code(201);
                echo json_encode([
                    "message" => "User created!",
                    "id" => DB::lastId(),
                ]);
                break;

            default:
                http_response_code(405);
                header("Allow: GET, POST"); 
        }
    }
}