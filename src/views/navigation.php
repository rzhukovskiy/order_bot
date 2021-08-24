<?php
/**
 * @var $result CommandEntity[]
 */

use Orderbot\Entities\CommandEntity;

foreach ($result as $command) {
    echo "<a href='/?command={$command->name}'>{$command->displayName}</a><br />";
}