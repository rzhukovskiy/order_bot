<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\EquipmentEntity;
use PDO;

class EquipmentModel extends BaseModel
{
    public static $nameTable = 'equipment';

    /**
     * @param int $id
     * @return EquipmentEntity
     */
    public static function getById(int $id): ?EquipmentEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable .
            "` WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new EquipmentEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @return EquipmentEntity[]
     */
    public static function getAll(): array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable . "`");
        $stmt->execute();

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new EquipmentEntity($row);
        }
        return $res;
    }

    /**
     * @return EquipmentEntity[]
     */
    public static function getAllActive(): array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable . "` WHERE `is_active` = 1");
        $stmt->execute();

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new EquipmentEntity($row);
        }
        return $res;
    }
}