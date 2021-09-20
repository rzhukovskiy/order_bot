<?php

namespace Orderbot\Services;

use Exception;
use Orderbot\Entities\UserEntity;
use Orderbot\Models\UserModel;
use Orderbot\Result;

class UserService
{
    private static $current;

    /**
     * @param int $chatId
     */
    public static function setCurrentByChatId(int $chatId)
    {
        static::$current = UserModel::getByChatId($chatId);
    }

    /**
     * @return UserEntity
     */
    public static function getCurrent(): UserEntity
    {
        return static::$current;
    }

    /**
     * @param array $data
     * @return Result
     */
    public function create(array $data): Result
    {
        $entity = new UserEntity($data);
        try {
            $entity->save();
            $res = new Result([
                'message' => "Юзер {$entity->name} создан",
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
        $entity = new UserEntity($data);

        try {
            $userId = $entity->save();
            $res = new Result([
                'message' => "Юзер " . UserModel::getById($userId)->name . " изменен",
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
            $entity = UserModel::getById($data['id']);

            try {
                $entity->delete();
                $res = new Result([
                    'message' => "Юзер {$entity->name} удален",
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
            'result' => UserModel::getAll(),
        ]);
    }
}