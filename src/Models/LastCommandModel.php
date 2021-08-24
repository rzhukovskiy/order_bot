<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\CommandEntity;
use PDO;

class LastCommandModel extends BaseModel
{
    public static $nameTable = 'last_command';

    /**
     * @param int $chatId
     * @return CommandEntity
     */
    public static function getByUser($chatId)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE chat_id = :chat_id ORDER BY created_at DESC");
        $stmt->execute([
            'chat_id' => $chatId,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $res = CommandModel::getById($row['command_id']);
            $res->appendParamsFromString($row['params']);
            $res->completed = $row['completed'];
        }

        return $res;
    }
}