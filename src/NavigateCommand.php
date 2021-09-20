<?php

namespace Orderbot;

use Orderbot\Entities\InstructionEntity;
use Orderbot\Models\InstructionModel;
use Orderbot\Services\UserService;

class NavigateCommand implements Interfaces\Command
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
        $this->instruction->completed = true;
        $this->instruction->saveAsLastInstruction();

        return new Result([
            'is_main' => !$this->instruction->parentId,
            'message' => 'Что делаем?',
            'result' => InstructionModel::getByParentAndRole(
                $this->instruction->id,
                UserService::getCurrent()->role
            )
        ]);
    }

    /**
     * @inheritDoc
     */
    public function setInstruction(InstructionEntity $instruction): void
    {
        $this->instruction = $instruction;
    }
}