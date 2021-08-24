<?php

namespace Orderbot\Entities;

use Orderbot\BaseEntity;
use Orderbot\Models\ClientModel;

class ClientEntity extends BaseEntity
{
    public function create($data)
    {
        ClientModel::save($data);
    }
}