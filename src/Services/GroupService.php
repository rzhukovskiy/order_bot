<?php

namespace Orderbot\Services;

use Exception;
use Orderbot\Entities\GroupEntity;
use Orderbot\Models\GroupModel;
use Orderbot\Result;

class GroupService
{
    /**
     * @param array $data
     * @return Result
     */
    public function create(array $data): Result
    {
        $group = new GroupEntity($data);
        try {
            $group->save();
            $res = new Result([
                'message' => "Группа расходов {$group->name} создана",
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
        $group = new GroupEntity($data);

        try {
            $group->save();
            $res = new Result([
                'message' => "Группа {$group->name} изменена",
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
            $group = GroupModel::getById($data['id']);

            try {
                $group->delete();
                $res = new Result([
                    'message' => "Группа {$group->name} удалена",
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
            'result' => GroupModel::getAllActive(),
        ]);
    }
}