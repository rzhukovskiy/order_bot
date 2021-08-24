<?php

namespace Orderbot\Entities;

use Orderbot\BaseEntity;
use Orderbot\Models\CommandParamModel;
use Orderbot\Models\LastCommandModel;

/**
 * @property integer    $id
 * @property integer    $parentId
 * @property integer    $type
 * @property string     $name
 * @property string     $displayName
 * @property array      $params
 * @property integer    $createdAt
 * @property bool       $completed
 */
class CommandEntity extends BaseEntity
{
    const TYPE_NAVIGATION = 1;
    const TYPE_EXECUTABLE = 2;

    public function __construct($data)
    {
        if(isset($data['params'])) {
            $data['params'] = unserialize($data['params']);
        } else {
            $data['params'] = [];
        }

        parent::__construct($data);
    }

    public function execute()
    {
        list($method, $className) = explode('_', $this->name);
        $fullClassName = 'Orderbot\\Entities\\' . ucfirst($className) . 'Entity';
        $obj = new $fullClassName($this->params);
        $obj->{$method}($this->params);
        $this->completed = true;
    }

    /**
     * @return int
     */
    public function getLastParamOrder()
    {
        $lastParamId = count($this->params) - 1;
        if ($lastParamId >= 0) {
            $name = array_keys($this->params)[$lastParamId];
            return CommandParamModel::getByName($this->id, $name)->order;
        } else {
            return 0;
        }
    }

    public function saveAsLastCommand()
    {
        $dataToSave = [
            'chat_id' => 1,
            'command_id' => $this->id,
            'params' => serialize($this->params),
            'created_at' => time(),
            'completed' => $this->completed,
        ];
        LastCommandModel::save($dataToSave);
    }

    /**
     * @param string $params
     */
    public function appendParamsFromString($params)
    {
        $paramList = unserialize($params);
        $this->params = array_merge($paramList, $this->params);
    }

    /**
     * @param array $params
     */
    public function appendParams($params)
    {
        $this->params = array_merge($this->params, $params);
    }
}