<?php

namespace Orderbot\Entities;

use Orderbot\BaseEntity;
use Orderbot\Interfaces\SearchResult;
use Orderbot\Models\UnitModel;

/**
 * @property int $id
 * @property int $cost
 * @property string $name
 * @property int $isActive
 */
class UnitEntity extends BaseEntity implements SearchResult
{
    /**
     * @return int
     */
    public function save(): int
    {
        $this->id = UnitModel::save($this->data);

        return $this->id;
    }

    /**
     * @return int
     */
    public function delete(): int
    {
        $this->isActive = 0;
        $this->id = UnitModel::save($this->data);

        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getAction(): int
    {
        return $this->id;
    }
}