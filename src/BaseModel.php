<?php

namespace Orderbot;

use PDO;

class BaseModel
{
    /** @var  $pdo PDO */
    protected static $pdo;
    protected static $nameTable;

    public static function init()
    {
        $dsn = 'mysql:dbname=orderbot;host=127.0.0.1';
        $user = 'root';
        $password = '11223344';

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        self::$pdo = new PDO($dsn, $user, $password, $opt);
    }

    private function __construct()
    {
    }

    /**
     * @param $data
     * @return string
     */
    public static function save($data)
    {
        $columns = implode("`, `", array_keys($data));
        $values  = implode(", :", array_keys($data));

        $updates = [];
        foreach ($data as $name => $value) {
            $data[$name . '1'] = $value;
            $updates[] = "`$name` = :{$name}1";
        }
        $updates = implode(", ", $updates);

        $stmt = self::$pdo->prepare("INSERT INTO " . static::$nameTable . " (`$columns`) VALUES (:$values)" .
            " ON DUPLICATE KEY UPDATE $updates");
        $stmt->execute($data);

        if (empty($data['id'])) {
            return self::$pdo->lastInsertId();
        } else {
            return $data['id'];
        }
    }

    /**
     * @param $params
     * @return bool
     */
    public static function delete($params)
    {
        $updates = [];
        foreach ($params as $name => $value) {
            $updates[] = "`$name` = :$name";
        }
        $updates = implode(", ", $updates);

        if (empty($params['id'])) {
            $stmt = self::$pdo->prepare("DELETE FROM " . static::$nameTable . " WHERE $updates");
            return $stmt->execute($params);
        } else {
            $stmt = self::$pdo->prepare("DELETE FROM " . static::$nameTable . " WHERE id = :id");
            return $stmt->execute([
                'id' => $params['id'],
            ]);
        }
    }
}