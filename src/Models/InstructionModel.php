<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\InstructionEntity;
use PDO;

class InstructionModel extends BaseModel
{
    public static $nameTable = 'instruction';

    /**
     * @param int $id
     * @return InstructionEntity
     */
    public static function getById(int $id): ?InstructionEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new InstructionEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @param string $name
     * @param int $role
     * @return InstructionEntity
     */
    public static function getByName(string $name, int $role): ?InstructionEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE name = :name AND role = :role");
        $stmt->execute([
            'name' => $name,
            'role' => $role,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new InstructionEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @param string $name
     * @param int $role
     * @return InstructionEntity
     */
    public static function getByDisplayName(string $name, int $role): ?InstructionEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE display_name = :name AND role = :role");
        $stmt->execute([
            'name' => $name,
            'role' => $role,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new InstructionEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @param int $parentId
     * @param int $role
     * @return InstructionEntity[]
     */
    public static function getByParentAndRole(int $parentId, int $role): array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE `parent_id` = :parent_id AND role = :role ORDER BY `order`");
        $stmt->execute([
            'parent_id' => $parentId,
            'role' => $role,
        ]);

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new InstructionEntity($row);
        }
        return $res;
    }
}