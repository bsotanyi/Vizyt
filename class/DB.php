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
        return self::query($sql, $vars, null, 'fetchColumn');
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

    // DB::insertOrUpdate('table', [
    //     'id' => 14,
    //     'name' => '[555]',
    // ]);
    public static function insertOrUpdate($table, $vars) {
        $columns = array_keys($vars);
        $columns_string = join(', ', array_keys($vars));

        $value_keys = join(', ', array_map(function($item) {
            return ':' . $item;
        }, $columns));

        $update_strings = join(', ', array_map(function($item) {
            return "$item = VALUES($item)"; 
        }, $columns));

        $sql = "INSERT INTO $table ($columns_string) VALUES ($value_keys) ON DUPLICATE KEY UPDATE $update_strings";
        return self::query($sql, $vars);
    }
}