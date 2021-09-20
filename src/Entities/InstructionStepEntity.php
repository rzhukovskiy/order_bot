<?php

namespace Orderbot\Entities;

use Orderbot\BaseEntity;

/**
 * @property int        $id
 * @property string     $name
 * @property integer    $instructionId
 * @property string     $description
 * @property int        $order
 * @property string     $content
 * @property int        $type
 * @property int        $nextId
 */
class InstructionStepEntity extends BaseEntity
{
    const TYPE_TEXT   = 1;
    const TYPE_LIST   = 2;
    const TYPE_METHOD = 3;
    const TYPE_NEXT   = 4;
    const TYPE_END    = 5;
}