<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\ExpenseEntity;
use PDO;

class ExpenseModel extends BaseModel
{
    public static $nameTable = 'expense';

    /**
     * @param int $id
     * @return ExpenseEntity
     */
    public static function getById(int $id): ?ExpenseEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable .
            "` WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new ExpenseEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @return ExpenseEntity[]
     */
    public static function getAll(): array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable . "`");
        $stmt->execute();

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new ExpenseEntity($row);
        }
        return $res;
    }

    /**
     * @param array $data
     * @return ExpenseEntity[]
     */
    public static function getAllActiveByGroupId(array $data): array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable .
            "` WHERE `is_active` = 1 AND group_id = :group_id");
        $stmt->execute([
            'group_id' => $data['group_id'],
        ]);

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new ExpenseEntity($row);
        }
        return $res;
    }
}