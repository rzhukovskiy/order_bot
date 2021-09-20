<?php

namespace Orderbot\Models;

use Orderbot\BaseModel;
use Orderbot\Entities\InstructionEntity;
use PDO;

class LastInstructionModel extends BaseModel
{
    public static $nameTable = 'last_instruction';

    /**
     * @param int $chatId
     * @return InstructionEntity
     */
    public static function getByChatId(int $chatId): ?InstructionEntity
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE chat_id = :chat_id ORDER BY created_at DESC");
        $stmt->execute([
            'chat_id' => $chatId,
        ]);

        $res = null;
        if ($stmt->rowCount()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $res = InstructionModel::getById($row['command_id']);
            if ($row['params']) {
                $res->appendParamsFromString($row['params']);
            }
            $res->completed = $row['completed'];
            $res->instructionStepId = $row['instruction_step_id'];
        }

        return $res;
    }
}