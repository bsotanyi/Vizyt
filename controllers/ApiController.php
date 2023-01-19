<?php

declare(strict_types=1);

class ApiController
{
    public static function api()
    {
        require_once 'config.php';
        header('Content-type: application/json; CHARSET=UTF-8');

        $parts = explode("/", $_SERVER['REQUEST_URI']);

        if ($parts[1] !== 'api') {
            http_response_code(404);
            die;
        }

        $controller = new ApiController;

        if ($parts[2] == 'users') {
            $id = $parts[3] ?? '';
            $controller->processRequest($_SERVER['REQUEST_METHOD'], $id, $parts[2]);

        } elseif (str_contains($parts[2], 'login')) {
            $email = $_GET['email'] ?? '';
            $password = $_GET['password'] ?? '';
            if (empty($email) || empty($password)) {
                http_response_code(404);
                die;
            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $controller->processRequest($_SERVER['REQUEST_METHOD'], [$email, $password]);
            } else {
                http_response_code(404);
                die;
            }

        } elseif (str_contains($parts[2], 'join')) {
            $token = $_GET['token'] ?? '';
            $id = $_GET['id'] ?? '';
            if (!empty($token) && !empty($id)) {
                $controller->processRequest($_SERVER['REQUEST_METHOD'], [$token, $id]);
            } else {
                http_response_code(404);
                die;
            }

        } elseif (str_contains($parts[2], 'invites')) {
            $id = $_GET['eventId'] ?? '';
            if (!empty($id)) {
                $controller->processResourceRequestInvites($_SERVER['REQUEST_METHOD'], $id, $parts[2]);
            } else {
                http_response_code(404);
                die;
            }

        } elseif (str_contains($parts[2], 'events')) {
            $id = $parts[3] ?? '';
            $controller->processRequest($_SERVER['REQUEST_METHOD'], $id, $parts[2]);

        } else {
            http_response_code(404);
            die;
        }
    }

    public function processRequest($method, $options, $table = null)
    {
        // $options = (int) $options;
        if ($options) {
            if (preg_match_all("([0-9]+)", $options[0])) {
                $this->processResourceRequest($method, $options, $table);
            } elseif (filter_var($options[0], FILTER_VALIDATE_EMAIL)) {
                $this->processResourceRequestLogin($method, $options);
            } elseif (preg_match_all('([a-z0-9]+)', $options[0])) {
                $this->processResourceRequestJoin($method, $options);
            }
        } else {
            $this->processCollectionRequestUser($method, $table);
        }
    }

    private function processResourceRequest ($method, $id, $table)
    {
        $row = DB::fetchRow("SELECT * FROM $table WHERE id = :id", ['id' => $id]);

        if (empty($row)) {
            http_response_code(404);
            echo json_encode(["message" => ucfirst($table) . " not found!"]);
            return;
        }

        switch ($method) {
            case "GET":
                echo json_encode($row);
                break;

            case "PATCH":
                if ($table == 'users') {
                    $data = (array) json_decode(file_get_contents("php://input"), true);
                    $password = $data['password'] ?? '';
                    if (!empty($password)) {
                        $password = password_hash($data['password'], PASSWORD_BCRYPT);
                    } else {
                        $password = $row['password'];
                    }
    
                    DB::query("UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, password = :password WHERE id = :id", [
                        'firstname' => $data['firstname'] ?? $row['firstname'],
                        'lastname' => $data['lastname'] ?? $row['lastname'],
                        'email' => $data['email'] ?? $row['email'],
                        'password' =>  $password,
                        'id' => $row['id']
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
                } else {
                    http_response_code(405);
                    header("Allow: GET, DELETE");
                }
                
                break;

            case "DELETE":
                DB::query("DELETE FROM $table WHERE id = :id", ['id' => $id]);

                if (DB::$affected_rows > 0) {
                    echo json_encode([
                        "message" => ucfirst($table) . " $id deleted!",
                        "rows" => DB::$affected_rows
                    ]);
                } else {
                    http_response_code(422);
                    echo json_encode([
                        "message" => "Failed to delete!"
                    ]);
                }
                break;

            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
        }
    }

    private function processCollectionRequestUser($method, $table)
    {
        switch ($method) {
            case "GET":
                $items = DB::query("SELECT * FROM $table");
                echo json_encode($items);
                break;

            case "POST":
                if ($table == 'users') {
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
                } else {
                    http_response_code(405);
                    header("Allow: GET");
                }
                
                break;

            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    private function processResourceRequestLogin($method, $data)
    {
        $user = DB::fetchRow("SELECT * FROM users WHERE email = :email", ['email' => $data[0]]);

        if (empty($user)) {
            http_response_code(404);
            echo json_encode(["message" => "User not found!"]);
            return;
        }

        switch ($method) {
            case "GET":
                if ($user['email'] == $data[0] && password_verify($data[1], $user['password'])) {
                    echo json_encode($user);
                } else {
                    http_response_code(400);
                    echo json_encode(["message" => "Incorrect password!"]);
                    die;
                }
                break;

            default:
                http_response_code(405);
                header("Allow: GET");
        }
    }

    private function processResourceRequestJoin($method, $data)
    {
        $email = DB::fetchValue("SELECT email FROM users WHERE id = :id", ['id' => $data[1]]);
        $event = DB::fetchRow("SELECT * FROM events WHERE qr_token = :qr", ['qr' => $data[0]]);
        $invites = json_decode($event['invites'], true);

        if (empty($event) || empty($email)) {
            http_response_code(404);
            echo json_encode(["message" => "Not found!"]);
            return;
        }
        
        foreach ($invites as $item) {
            if ($item['email'] == $email) {
                switch ($method) {
                    case "PATCH":
                        DB::query("UPDATE invites SET arrived = 1 WHERE receiver_email = :email", [ 'email' => $email ]);
                        echo json_encode(["message" => "User {$data['1']} successfully updated!"]);
                        http_response_code(200);
                        break;

                    default:
                        http_response_code(405);
                        header("Allow: PATCH");
                }
            } else {
                http_response_code(400);
            }
        }
    }

    private function processResourceRequestInvites ($method, $options) 
    {
        $invites = DB::query("SELECT u.firstname, u.lastname, u.email, i.event_id, i.arrived FROM invites i INNER JOIN users u ON u.email = i.receiver_email WHERE i.event_id = :id", [ 'id' => $options ]);
        switch ($method) {
            case "GET":
                echo json_encode($invites);
                break;
            default:
                http_response_code(405);
                header("Allow: GET");
            }
    }
}
