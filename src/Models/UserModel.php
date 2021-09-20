<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\UserEntity;
use PDO;

class UserModel extends BaseModel
{
    public static $nameTable = 'user';

    /**
     * @param int $id
     * @return UserEntity
     */
    public static function getById(int $id): ?UserEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new UserEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @param int $chatId
     * @return UserEntity
     */
    public static function getByChatId(int $chatId): ?UserEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE chat_id = :chat_id");
        $stmt->execute([
            'chat_id' => $chatId,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new UserEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @return UserEntity[]
     */
    public static function getByRole(int $role): array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE role = :role");
        $stmt->execute([
            'role' => $role,
        ]);

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new UserEntity($row);
        }
        return $res;
    }

    /**
     * @return UserEntity[]
     */
    public static function getAll(): array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM `" . self::$nameTable . "`");
        $stmt->execute();

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new UserEntity($row);
        }
        return $res;
    }
}