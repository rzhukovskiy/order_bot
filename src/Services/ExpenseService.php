<?php

namespace Orderbot\Services;

use Exception;
use Orderbot\Entities\ExpenseEntity;
use Orderbot\Models\ExpenseModel;
use Orderbot\Result;

class ExpenseService
{
    /**
     * @param array $data
     * @return Result
     */
    public function create(array $data): Result
    {
        $expense = new ExpenseEntity($data);
        try {
            $expense->save();
            $res = new Result([
                'message' => "Расход {$expense->name} создан",
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
        $expense = new ExpenseEntity($data);

        try {
            $expense->save();
            $res = new Result([
                'message' => "Расход {$expense->name} изменен",
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
            $expense = ExpenseModel::getById($data['id']);

            try {
                $expense->delete();
                $res = new Result([
                    'message' => "Расход {$expense->name} удален",
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
     * @param array $data
     * @return Result
     */
    public function search(array $data): Result
    {
        return new Result([
            'result' => ExpenseModel::getAllActiveByGroupId($data),
        ]);
    }
}