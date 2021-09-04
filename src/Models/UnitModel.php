<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\UnitEntity;
use PDO;

class UnitModel extends BaseModel
{
    public static $nameTable = 'unit';

    /**
     * @param int $id
     * @return UnitEntity
     */
    public static function getById(int $id): ?UnitEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable .
            "` WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new UnitEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @return UnitEntity[]
     */
    public static function getAll(): array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable . "`");
        $stmt->execute();

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new UnitEntity($row);
        }
        return $res;
    }

    /**
     * @return UnitEntity[]
     */
    public static function getAllActive(): array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable . "` WHERE `is_active` = 1");
        $stmt->execute();

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new UnitEntity($row);
        }
        return $res;
    }
}