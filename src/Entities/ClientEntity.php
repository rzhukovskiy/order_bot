<?php

namespace Orderbot\Entities;

use Orderbot\BaseEntity;
use Orderbot\Interfaces\SearchResult;
use Orderbot\Models\ClientModel;

/**
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $contact
 * @property string $phone
 */
class ClientEntity extends BaseEntity implements SearchResult
{
    /**
     * @return int
     */
    public function save(): int
    {
        $contactName = null;
        $phone = null;

        if ($this->contact) {
            $contactName = $this->contact;
            $phone = $this->phone;

            $this->deleteProperty('contact');
            $this->deleteProperty('phone');
        }

        $this->id = ClientModel::save($this->data);

        if ($contactName) {
            $contact = new ContactEntity([
                'name' => $contactName,
                'phone' => $phone,
                'client_id' => $this->id,
            ]);
            $contact->save();
        }

        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return "{$this->name} ({$this->address})";
    }

    /**
     * @return int
     */
    public function getAction(): int
    {
        return $this->id;
    }
}