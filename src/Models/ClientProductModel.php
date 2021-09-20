<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\ClientProductEntity;
use PDO;

class ClientProductModel extends BaseModel
{
    public static $nameTable = 'client_product';

    /**
     * @param int $id
     * @return ClientProductEntity
     */
    public static function getById(int $id): ClientProductEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new ClientProductEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @param int $clientId
     * @return null|ClientProductEntity[]
     */
    public static function getByClientId(int $clientId): ?array
    {
        $stmt = self::$pdo->prepare("SELECT " . self::$nameTable .".*, " . ProductModel::$nameTable . ".name FROM " .
            self::$nameTable . ", " . ProductModel::$nameTable .
            " WHERE product_id = " . ProductModel::$nameTable . ".id AND client_id = :client_id");
        $stmt->execute([
            'client_id' => $clientId,
        ]);

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = new ClientProductEntity($row);
        }
        return $res;
    }
}