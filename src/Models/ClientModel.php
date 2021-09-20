<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\ClientEntity;
use PDO;

class ClientModel extends BaseModel
{
    public static $nameTable = 'client';

    /**
     * @param string $field
     * @param string $value
     * @return ClientEntity[]
     */
    public static function findByFieldValue(string $field, string $value):array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE LOWER(`$field`) LIKE LOWER(:value)");
        $stmt->execute([
            'value' => '%' . $value . '%'
        ]);

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new ClientEntity($row);
        }
        return $res;
    }

    /**
     * @param int $id
     * @return null|ClientEntity
     */
    public static function getById(int $id): ?ClientEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new ClientEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @param string $name
     * @return null|ClientEntity
     */
    public static function getByName(string $name): ?ClientEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE name = :name");
        $stmt->execute([
            'name' => $name,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new ClientEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }
}