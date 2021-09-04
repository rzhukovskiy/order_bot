<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\MaterialEntity;
use PDO;

class MaterialModel extends BaseModel
{
    public static $nameTable = 'material';

    /**
     * @param int $id
     * @return MaterialEntity
     */
    public static function getById(int $id): ?MaterialEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable .
            "` WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new MaterialEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @return MaterialEntity[]
     */
    public static function getAll(): array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable . "`");
        $stmt->execute();

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new MaterialEntity($row);
        }
        return $res;
    }

    /**
     * @return MaterialEntity[]
     */
    public static function getAllActive(): array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable . "` WHERE `is_active` = 1");
        $stmt->execute();

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new MaterialEntity($row);
        }
        return $res;
    }
}