<?php

namespace Orderbot\Entities;

use Orderbot\BaseEntity;
use Orderbot\Models\UserModel;

/**
 * @property integer    $id
 * @property integer    $role
 * @property string     $name
 */
class UserEntity extends BaseEntity
{
    const ROLE_ADMIN = 1;
    const ROLE_MANAGER = 2;
    const ROLE_FINANCE = 3;
    const ROLE_FOREMAN = 4;
    const ROLE_DRIVER = 5;
    const ROLE_BLOCKED = 6;

    private static $roleNames = [
        self::ROLE_ADMIN => 'Админ',
        self::ROLE_MANAGER => 'Менеджер',
        self::ROLE_FINANCE => 'Бухгалтер',
        self::ROLE_FOREMAN => 'Бригадир',
        self::ROLE_DRIVER => 'Водитель',
        self::ROLE_BLOCKED => 'Заблокирован',
    ];
    /**
     * @return int
     */
    public function save(): int
    {
        $this->id = UserModel::save($this->data);

        return $this->id;
    }
    /**
     * @return int
     */
    public function delete(): int
    {
        $this->role = self::ROLE_BLOCKED;
        $this->id = UserModel::save($this->data);

        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->name . " - " . self::$roleNames[$this->role];
    }

    /**
     * @return int
     */
    public function getAction(): int
    {
        return $this->id;
    }

}