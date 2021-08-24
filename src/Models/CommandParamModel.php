<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\CommandParamEntity;
use PDO;

class CommandParamModel extends BaseModel
{
    public static $nameTable = 'command_param';

    /**
     * @param int $commandId
     * @param string $name
     * @return CommandParamEntity
     */
    public static function getByName($commandId, $name)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE `command_id` = :command_id AND `name` = :name");
        $stmt->execute([
            'command_id' => $commandId,
            'name' => $name,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new CommandParamEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @param int $commandId
     * @param int $order
     * @return CommandParamEntity
     */
    public static function getNext($commandId, $order = 0)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE `command_id` = :command_id AND `order` > :order ORDER BY `order`");
        $stmt->execute([
            'command_id' => $commandId,
            'order' => $order,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new CommandParamEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }
}