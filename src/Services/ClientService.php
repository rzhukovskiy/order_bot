<?php

namespace Orderbot\Services;

use Exception;
use Orderbot\Entities\ClientEntity;
use Orderbot\Models\ClientModel;
use Orderbot\Models\ContactModel;
use Orderbot\Result;

class ClientService
{
    /**
     * @param array $data
     * @return Result
     */
    public function create(array $data): Result
    {
        $client = new ClientEntity($data);
        try {
            $client->save();
            $res = new Result([
                'message' => "Клиент {$client->name} создан",
            ]);
        } catch (Exception $ex) {
            $res = new Result([
                'message' => "Что-то наебнулось: {$ex->getMessage()}",
            ]);
        }
        return $res;
    }

    /**
     * @param array $data
     * @return Result
     */
    public function update(array $data): Result
    {
        if (isset($data['contact_id'])) {
            try {
                $contact = ContactModel::getById($data['contact_id']);
                $contact->delete();
                $res = new Result([
                    'message' => "Контакт {$contact->name} удален",
                ]);
            } catch (Exception $ex) {
                $res = new Result([
                    'message' => "Что-то наебнулось: {$ex->getMessage()}",
                ]);
            }

            return $res;
        }

        unset($data['search'], $data['search_value'], $data['next_id']);
        $client = new ClientEntity($data);

        try {
            $client->save();
            $res = new Result([
                'message' => "Клиент {$client->name} изменен",
            ]);
        } catch (Exception $ex) {
            $res = new Result([
                'message' => "Что-то наебнулось: {$ex->getMessage()}",
            ]);
        }
        return $res;
    }

    /**
     * @param array $data
     * @return Result
     */
    public function search(array $data): Result
    {
        if ($data['search'] == 'phone') {
            return new Result([
                'result' => ContactModel::findClientByPhone($data['search'], $data['search_value']),
            ]);
        } else {
            return new Result([
                'result' => ClientModel::findByFieldValue($data['search'], $data['search_value']),
            ]);
        }
    }

    /**
     * @param array $data
     * @return Result
     */
    public function contact(array $data): Result
    {
        return new Result([
            'result' => ContactModel::findByClient($data['id']),
        ]);
    }
}