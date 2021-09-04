<?php

namespace Orderbot\Interfaces;

use Orderbot\Entities\InstructionEntity;
use Orderbot\Result;

interface Command
{
    /**
     * @return Result
     */
    public function execute(): Result;

    /**
     * @param InstructionEntity $instruction
     * @return void
     */
    public function setInstruction(InstructionEntity $instruction): void;
}