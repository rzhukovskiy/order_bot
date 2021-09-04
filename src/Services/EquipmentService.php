<?php

namespace Orderbot\Services;

use Exception;
use Orderbot\Entities\EquipmentEntity;
use Orderbot\Models\EquipmentModel;
use Orderbot\Result;

class EquipmentService
{
    /**
     * @param array $data
     * @return Result
     */
    public function create(array $data): Result
    {
        $entity = new EquipmentEntity($data);
        try {
            $entity->save();
            $res = new Result([
                'message' => "Оборудование $entity->name создано",
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
        $entity = new EquipmentEntity($data);

        try {
            $entity->save();
            $res = new Result([
                'message' => "Оборудование $entity->name изменено",
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
            $entity = EquipmentModel::getById($data['id']);

            try {
                $entity->delete();
                $res = new Result([
                    'message' => "Оборудование $entity->name удалено",
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
            'result' => EquipmentModel::getAllActive(),
        ]);
    }
}