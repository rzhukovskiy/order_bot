<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\InstructionStepEntity;
use PDO;

class InstructionStepModel extends BaseModel
{
    public static $nameTable = 'instruction_step';

    /**
     * @param null|int $id
     * @return null|InstructionStepEntity
     */
    public static function getById(?int $id): ?InstructionStepEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE `id` = :id ");
        $stmt->execute([
            'id' => $id,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new InstructionStepEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @param int $instructionId
     * @param string $name
     * @return null|InstructionStepEntity
     */
    public static function getByName(int $instructionId, string $name): ?InstructionStepEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE `instruction_id` = :command_id AND `name` = :name");
        $stmt->execute([
            'command_id' => $instructionId,
            'name' => $name,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new InstructionStepEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }

    /**
     * @param int $instructionId
     * @return null|InstructionStepEntity
     */
    public static function getFirst(int $instructionId): ?InstructionStepEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE `instruction_id` = :instruction_id ORDER BY `order` LIMIT 1");
        $stmt->execute([
            'instruction_id' => $instructionId,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $res = new InstructionStepEntity($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return $res;
    }
}