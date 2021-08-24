<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\UserEntity;
use PDO;

class UserModel extends BaseModel
{
    public static $nameTable = 'command';

    /**
     * @param int $id
     * @return UserEntity
     */
    public static function getById($id)
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
}