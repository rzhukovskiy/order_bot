<?php
/**
 * @var $result InstructionEntity[]
 * @var $this Result
 */

use Orderbot\Result;

$result = $this->getResult();

use Orderbot\Entities\InstructionEntity;

foreach ($result as $command) {
    echo "<a href='/?command={$command->name}'>{$command->displayName}</a><br />";
}