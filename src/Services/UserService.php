<?php

namespace Orderbot\Services;

use Orderbot\Entities\UserEntity;
use Orderbot\Models\UserModel;

class UserService
{
    /**
     * @return int
     */
    public static function getCurrentId()
    {
        return 1;
    }
    /**
     * @return UserEntity
     */
    public static function getCurrent()
    {
        return UserModel::getById(self::getCurrentId());
    }
}