<?php

namespace Orderbot;

use Orderbot\Entities\InstructionEntity;
use Orderbot\Entities\InstructionStepEntity;
use Orderbot\Models\InstructionModel;
use Orderbot\Services\CommandService;

class CreateCommand implements Interfaces\Command
{
    /**
     * @var InstructionEntity
     */
    private $instruction;

    /**
     * @inheritDoc
     */
    public function execute(): Result
    {
        $nextStep = $this->instruction->getNextStep();

        if ($nextStep) {
            $this->instruction->instructionStepId = $nextStep->id;
            $res = new Result([
                'message' => $nextStep->description,
                'step' => $nextStep,
            ]);

            if (in_array($nextStep->type, [
                InstructionStepEntity::TYPE_METHOD,
                InstructionStepEntity::TYPE_END,
            ])) {
                $instructionForParam = new InstructionEntity([
                    'method' => $nextStep->content,
                    'params' => $this->instruction->params,
                ]);

                $res->merge($instructionForParam->run());
            }
        }
        if (!$nextStep || $nextStep->type == InstructionStepEntity::TYPE_END) {
            $res = $this->instruction->run();

            $nextInstructionId = $this->instruction->nextId ?: $this->instruction->parentId;
            $nextInstruction = InstructionModel::getById($nextInstructionId);
            $nextInstruction->chatId = $this->instruction->chatId;

            $res->merge(CommandService::executeInstruction($nextInstruction, null));
        }

        $this->instruction->saveAsLastInstruction();

        return $res;
    }

    /**
     * @inheritDoc
     */
    public function setInstruction(InstructionEntity $instruction): void
    {
        $this->instruction = $instruction;
    }
}