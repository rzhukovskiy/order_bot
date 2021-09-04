<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\ClientEntity;
use Orderbot\Entities\ContactEntity;
use PDO;

class ContactModel extends BaseModel
{
    public static $nameTable = 'contact';

    /**
     * @param string $field
     * @param string $value
     * @return ClientEntity[]
     */
    public static function findClientByPhone(string $field, string $value):array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE LOWER(`$field`) LIKE LOWER(:value)");
        $stmt->execute([
            'value' => '%' . $value . '%'
        ]);

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = ClientModel::getById($row['client_id']);
        }
        return $res;
    }

    /**
     * @param int $clientId
     * @return ContactEntity[]
     */
    public static function findByClient(int $clientId):array
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE client_id = :client_id");
        $stmt->execute([
            'client_id' => $clientId
        ]);

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = ContactModel::getById($row['id']);
        }
        return $res;
    }

    /**
     * @param int $id
     * @return ContactEntity
     */
    public static function getById(int $id): ContactEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new ContactEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }
}