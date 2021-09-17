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
            $res = new Result([
                'message' => $nextStep->description,
                'step' => $nextStep,
            ]);

            if ($nextStep->type == InstructionStepEntity::TYPE_METHOD) {
                $instructionForParam = new InstructionEntity([
                    'method' => $nextStep->content,
                    'params' => $this->instruction->params,
                ]);

                $res->merge($instructionForParam->run());
            }
        } else {
            $res = $this->instruction->run();

            $nextCommandId = $this->instruction->nextId ?: $this->instruction->parentId;
            $nextCommand = InstructionModel::getById($nextCommandId);

            $res->merge(CommandService::handleText($nextCommand->name, $res->getResult()));
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