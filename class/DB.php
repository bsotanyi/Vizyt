<?php

class DB {

    protected static $pdo;
    public static $affected_rows;

    public static function connect($host, $db, $username, $password, $charset) {

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $offset = date('P');

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone='$offset';",
        ];

        self::$pdo = new PDO($dsn, $username, $password, $options);
    }

    public static function query($sql, $vars = [], $fetch_type = PDO::FETCH_ASSOC, $fetch_function = 'fetchAll') {
        $data = null;
        // dump($sql, $vars);
        try {
            $stmt = self::$pdo->prepare($sql);
            $new_vars = [];
            foreach ($vars as $key => $value) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $new_vars[':' . $key] = $value;
            }
            $stmt->execute($new_vars);
            $data = $stmt->{$fetch_function}($fetch_type);
            self::$affected_rows = $stmt->rowCount();
        } catch (Exception $e) {
            echo '<pre>';
            throw new Exception($e->getMessage());
        }
        return $data;
    }

    public static function lastId() {
        return self::$pdo->lastInsertId();
    }

    public static function fetchValue($sql, $vars = []) {
        return self::query($sql, $vars, PDO::FETCH_NUM, 'fetch')[0] ?? null;
    }

    public static function fetchRow($sql, $vars = []) {
        return self::query($sql, $vars)[0] ?? [];
    }

    public static function fetchColumn($sql, $vars = []) {
        return self::query($sql, $vars, PDO::FETCH_COLUMN);
    }

    public static function fetchKeyPair($sql, $vars = []) {
        return self::query($sql, $vars, PDO::FETCH_KEY_PAIR);
    }

    public static function fetchByKey($sql, $vars = [], $key = 'id') {
        $data = self::query($sql, $vars);
        return array_combine(array_column($data, $key), array_values($data));
    }

    /**
     * Insert or update a row
     * 
     * @param $table
     * @param $seach_vars - Associative array to search for, if any record exists. Example: ['id' => 5, 'email' => 'user@example.org']
     * @param $update_vars - Associative array of values to update
     * 
     * Note - when no record is found, then the search and update variables both get inserted.
     * Example usage:
        DB::insertOrUpdate('users',
            [
                'id' => $_SESSION['user']['id'],
            ],
            [
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'email' => $_POST['email'],
            ],
        );
     */
    public static function insertOrUpdate($table, $search_vars, $update_vars = []) {
        $fields = [];
        if (empty($update_vars)) {
            $update_vars = $search_vars;
        } else {
            foreach ($search_vars as $column => $value) {
                $fields[] = "$column=:$column";
            }
            $search_sql = join(' AND ', $fields);
            $count = self::fetchValue("SELECT COUNT(*) FROM $table WHERE $search_sql", $search_vars);
        }

        if (empty($count ?? 0)) {
            foreach ($update_vars as $column => $value) {
                $fields[] = "$column=:$column";
            }
            $update_sql = join(', ', $fields);
            return self::query("INSERT INTO $table SET $update_sql", array_merge($search_vars, $update_vars));
        } else {
            $update_fields = [];
            foreach ($update_vars as $column => $value) {
                $update_fields[] = "$column=:$column";
            }
            $update_sql = join(', ', $update_fields);
            return self::query("UPDATE $table SET $update_sql WHERE $search_sql", array_merge($search_vars, $update_vars));
        }
    }
}