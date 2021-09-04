<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\GroupEntity;
use PDO;

class GroupModel extends BaseModel
{
    public static $nameTable = 'group';

    /**
     * @param int $id
     * @return GroupEntity
     */
    public static function getById(int $id): ?GroupEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable .
            "` WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new GroupEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @return GroupEntity[]
     */
    public static function getAll(): array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable . "`");
        $stmt->execute();

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new GroupEntity($row);
        }
        return $res;
    }

    /**
     * @return GroupEntity[]
     */
    public static function getAllActive(): array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable . "` WHERE `is_active` = 1");
        $stmt->execute();

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new GroupEntity($row);
        }
        return $res;
    }
}