<?php

namespace Orderbot\Services;

use Exception;
use Orderbot\Entities\ProductEntity;
use Orderbot\Models\ProductModel;
use Orderbot\Result;

class ProductService
{
    /**
     * @param array $data
     * @return Result
     */
    public function create(array $data): Result
    {
        $entity = new ProductEntity($data);
        try {
            $entity->save();
            $res = new Result([
                'message' => "Товар $entity->name создан",
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
        $entity = new ProductEntity($data);

        try {
            $entity->save();
            $res = new Result([
                'message' => "Товар $entity->name изменен",
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
    public function delete(array $data): Result
    {
        if (isset($data['id'])) {
            $entity = ProductModel::getById($data['id']);

            try {
                $entity->delete();
                $res = new Result([
                    'message' => "Товар $entity->name удален",
                ]);
            } catch (Exception $ex) {
                $res = new Result([
                    'message' => "Что-то наебнулось: {$ex->getMessage()}",
                ]);
            }
        } else {
            $res = new Result([
                'message' => "Нет ID поля",
            ]);
        }

        return $res;
    }

    /**
     * @return Result
     */
    public function search(): Result
    {
        return new Result([
            'result' => ProductModel::getAllActive(),
        ]);
    }
}