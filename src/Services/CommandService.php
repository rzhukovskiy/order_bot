<?php

namespace Orderbot\Services;

use Orderbot\Entities\InstructionEntity;
use Orderbot\Interfaces\Command;
use Orderbot\Models\InstructionModel;
use Orderbot\Models\InstructionStepModel;
use Orderbot\Models\LastInstructionModel;
use Orderbot\Result;

class CommandService
{
    const TYPE_NAVIGATION = 1;
    const TYPE_CREATION   = 2;

    private static $commandList = [
        self::TYPE_NAVIGATION   => 'navigate',
        self::TYPE_CREATION     => 'create',
    ];
    private static $firstCommand = 'start';

    /**
     * @param null|string $instructionText
     * @param null|array $params
     * @return Result
     */
    public static function handleText(?string $instructionText, ?array $params): Result
    {
        $instructionText = $instructionText ?: static::$firstCommand;
        $chatId = ChatService::getCurrentId();

        $previousInstruction = LastInstructionModel::getByUser($chatId);
        $currentInstruction = InstructionModel::getByName($instructionText);

        $continue = $previousInstruction
            && $currentInstruction->id == $previousInstruction->id
            && !$previousInstruction->completed;
        if ($continue) {
            $currentInstruction = $previousInstruction;
        }

        if ($params && count($params)) {
            $currentInstruction->appendParams($params);
        }

        $command = static::createCommandFromInstruction($currentInstruction);

        return $command->execute();
    }

    /**
     * @param InstructionEntity $instruction
     * @return Command
     */
    private static function createCommandFromInstruction(InstructionEntity $instruction): Command
    {
        $commandName = 'Orderbot\\' . ucfirst(static::$commandList[$instruction->type]) . 'Command';
        /** @var Command $command */
        $command = new $commandName();
        $command->setInstruction($instruction);

        return $command;
    }
}