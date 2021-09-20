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

    /**
     * @param Request $request
     * @return Result
     */
    public static function handleRequest(Request $request): Result
    {
        $chatId = $request->extractChatId();
        $previousInstruction = LastInstructionModel::getByChatId($chatId);
        $isPreviousInstructionCompleted = !$previousInstruction || $previousInstruction->completed;

        $params = $request->extractParams();
        if (isset($params[Request::INSTRUCTION_FIELD])) {
            $text = $params[Request::INSTRUCTION_FIELD];
            unset($params[Request::INSTRUCTION_FIELD]);
        } else {
            $text = $request->extractText();
        }

        if ($text[0] == '/') {
            $currentInstruction = InstructionModel::getByName($text, UserService::getCurrent()->role);
        } else {
            $currentInstruction = !$text ? null : InstructionModel::getByDisplayName($text, UserService::getCurrent()->role);
            if (!$currentInstruction && !$isPreviousInstructionCompleted) {
                $currentInstruction = $previousInstruction;
                $step = $currentInstruction->getStep();
                if ($text) {
                    $params[$step->name] = $text;
                }
                if ($step->type == InstructionStepEntity::TYPE_NEXT) {
                    $currentInstruction->nextInstructionStepId = $params[$step->name];
                }
            }
        }

        if (!$currentInstruction) {
            return new Result([
                'message' => 'На этом наши полномочия все',
            ]);
        }

        $currentInstruction->chatId = $chatId;

        return self::executeInstruction($currentInstruction, $params);
    }

    public static function executeInstruction(InstructionEntity $instruction, ?array $params): Result
    {
        if ($params && count($params)) {
            $instruction->appendParams($params);
        }
        
        $command = static::createCommandFromInstruction($instruction);

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