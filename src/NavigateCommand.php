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
        $this->instruction->saveAsLastInstruction();

        return new Result([
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