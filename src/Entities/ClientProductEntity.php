<?php

namespace Orderbot\Entities;

use Orderbot\BaseEntity;
use Orderbot\Interfaces\SearchResult;
use Orderbot\Models\ClientProductModel;

/**
 * @property int $id
 * @property int $price
 * @property int $productId
 * @property int $clientId
 * @property int $name
 */
class ClientProductEntity extends BaseEntity implements SearchResult
{
    /**
     * @return int
     */
    public function save(): int
    {
        $this->deleteProperty('name');
        $this->id = ClientProductModel::save($this->data);

        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->name . " (" . $this->price . ")";
    }

    /**
     * @return int
     */
    public function getAction(): int
    {
        return $this->id;
    }
}