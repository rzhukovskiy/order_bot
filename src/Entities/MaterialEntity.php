<?php

namespace Orderbot\Entities;

use Orderbot\BaseEntity;
use Orderbot\Interfaces\SearchResult;
use Orderbot\Models\MaterialModel;

/**
 * @property int $id
 * @property string $unit
 * @property string $name
 * @property int $isActive
 */
class MaterialEntity extends BaseEntity implements SearchResult
{
    /**
     * @return int
     */
    public function save(): int
    {
        $this->id = MaterialModel::save($this->data);

        return $this->id;
    }
    /**
     * @return int
     */
    public function delete(): int
    {
        $this->isActive = 0;
        $this->id = MaterialModel::save($this->data);

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