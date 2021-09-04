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
     * @param array $data
     * @return int
     */
    public static function save(array $data): int
    {
        if (isset($data['id'])) {
            $updates = [];
            foreach ($data as $name => $value) {
                $updates[] = "`$name` = :{$name}";
            }
            $data['id1'] = $data['id'];
            $updates = implode(", ", $updates);
            $stmt = self::$pdo->prepare("UPDATE `" . static::$nameTable . "` SET $updates WHERE id = :id1");
        } else {
            $columns = implode("`, `", array_keys($data));
            $values  = implode(", :", array_keys($data));

            $stmt = self::$pdo->prepare("INSERT INTO `" . static::$nameTable . "` (`$columns`) VALUES (:$values)");
        }
        $stmt->execute($data);

        if (empty($data['id'])) {
            return self::$pdo->lastInsertId();
        } else {
            return $data['id'];
        }
    }

    /**
     * @param array $params
     * @return bool
     */
    public static function delete(array $params): bool
    {
        $updates = [];
        foreach ($params as $name => $value) {
            $updates[] = "`$name` = :$name";
        }
        $updates = implode(", ", $updates);

        if (empty($params['id'])) {
            $stmt = self::$pdo->prepare("DELETE FROM `" . static::$nameTable . "` WHERE $updates");
            return $stmt->execute($params);
        } else {
            $stmt = self::$pdo->prepare("DELETE FROM `" . static::$nameTable . "` WHERE id = :id");
            return $stmt->execute([
                'id' => $params['id'],
            ]);
        }
    }
}