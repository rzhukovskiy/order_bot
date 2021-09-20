<?php

namespace Orderbot\Services;

use Exception;
use Orderbot\Entities\ClientEntity;
use Orderbot\Models\ClientModel;
use Orderbot\Models\ClientProductModel;
use Orderbot\Models\ContactModel;
use Orderbot\Models\ProductModel;
use Orderbot\Result;

class ClientService
{
    /**
     * @param array $data
     * @return Result
     */
    public function create(array $data): Result
    {
        $message = "Клиент {$data['name']} создан\n Адрес: {$data['address']}\n";
        $message .= "Контакт: {$data['contact']} ({$data['phone']})";

        return new Result([
            'message' => $message,
        ]);
    }

    /**
     * @param array $data
     * @return Result
     */
    public function product(array $data): Result
    {
        $productId = null;
        $productPrice = null;
        if (isset($data['product'])) {
            $productId = $data['product'];
            $productPrice = $data['price'];
        }
        unset($data['product'], $data['price'], $data['next_id']);

        try {
            $client = ClientModel::getByName($data['name']);
            if($client) {
                $clientId = $client->id;
            } else {
                $client = new ClientEntity($data);
                $clientId = $client->save();
            }

            if ($productId) {
                print_r([
                    'client_id' => $clientId,
                    'product_id' => $productId,
                    'price' => $productPrice,
                ]);
                ClientProductModel::save([
                    'client_id' => $clientId,
                    'product_id' => $productId,
                    'price' => $productPrice,
                ]);
            }

            $res = new Result([
                'result' => ProductModel::getAllActive(),
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

        if (isset($data['client_product_id'])) {
            try {
                $product = ClientProductModel::getById($data['client_product_id']);
                $product->price = $data['price'];
                $product->save();
                $res = new Result([
                    'message' => "Цена товара изменена",
                ]);
            } catch (Exception $ex) {
                $res = new Result([
                    'message' => "Что-то наебнулось: {$ex->getMessage()}",
                ]);
            }

            return $res;
        }

        unset($data['search'], $data['search_value'], $data['next_id'], $data['client_product_id'], $data['price']);
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
    public function price(array $data): Result
    {
        return new Result([
            'result' => ClientProductModel::getByClientId($data['id']),
        ]);
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