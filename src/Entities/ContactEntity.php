<?php

namespace Orderbot\Entities;

use Orderbot\BaseEntity;
use Orderbot\Interfaces\SearchResult;
use Orderbot\Models\ClientModel;
use Orderbot\Models\ContactModel;

/**
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property integer $clientId
 */
class ContactEntity extends BaseEntity implements SearchResult
{
    /**
     * @return int
     */
    public function save(): int
    {
        $this->id = ContactModel::save($this->data);

        return $this->id;
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        return ContactModel::delete($this->data);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return "{$this->name} ({$this->phone})";
    }

    /**
     * @return int
     */
    public function getAction(): int
    {
        return $this->id;
    }
}