<?php

namespace Orderbot\Services;

use Orderbot\Entities\InstructionEntity;
use Orderbot\Entities\InstructionStepEntity;
use Orderbot\Interfaces\Command;
use Orderbot\Models\InstructionModel;
use Orderbot\Models\InstructionStepModel;
use Orderbot\Models\LastInstructionModel;
use Orderbot\Request;
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
     * @param null|string $text
     * @param null|array $params
     * @return Result
     */
    public static function handleText(?string $text, ?array $params): Result
    {
        $text = $text ?? static::$firstCommand;
        $chatId = Request::extractChatId();

        $previousInstruction = LastInstructionModel::getByChatId($chatId);
        if ($text[0] == '/') {
            $currentInstruction = InstructionModel::getByName($text, UserService::getCurrent()->role);
        } else {
            if ($previousInstruction && !$previousInstruction->completed) {
                $currentInstruction = $previousInstruction;
                $nextStep = $currentInstruction->getNextStep();
                if ($nextStep->type == InstructionStepEntity::TYPE_TEXT) {
                    $params[$nextStep->name] = $text;
                }
            } else {
                $currentInstruction = InstructionModel::getByDisplayName($text, UserService::getCurrent()->role);
            }
        }

        if (!$currentInstruction) {
            return new Result([
                'message' => 'На этом наши полномочия все',
            ]);
        }

        if ($params && count($params)) {
            $currentInstruction->appendParams($params);
        }
        $currentInstruction->chatId = $chatId;

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