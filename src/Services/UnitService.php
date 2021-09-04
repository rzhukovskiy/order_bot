<?php

namespace Orderbot\Services;

use Exception;
use Orderbot\Entities\UnitEntity;
use Orderbot\Models\UnitModel;
use Orderbot\Result;

class UnitService
{
    /**
     * @param array $data
     * @return Result
     */
    public function create(array $data): Result
    {
        $entity = new UnitEntity($data);
        try {
            $entity->save();
            $res = new Result([
                'message' => "Юнит {$entity->name} создан",
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
        $entity = new UnitEntity($data);

        try {
            $entity->save();
            $res = new Result([
                'message' => "Юнит {$entity->name} изменен",
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
            $entity = UnitModel::getById($data['id']);

            try {
                $entity->delete();
                $res = new Result([
                    'message' => "Юнит {$entity->name} удален",
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
            'result' => UnitModel::getAllActive(),
        ]);
    }
}