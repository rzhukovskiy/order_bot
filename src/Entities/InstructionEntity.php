<?php

namespace Orderbot\Entities;

use Orderbot\BaseEntity;
use Orderbot\Models\InstructionStepModel;
use Orderbot\Models\LastInstructionModel;
use Orderbot\Result;

/**
 * @property integer    $id
 * @property integer    $chatId
 * @property integer    $parentId
 * @property integer    $nextId
 * @property integer    $type
 * @property string     $name
 * @property string     $displayName
 * @property array      $params
 * @property integer    $createdAt
 * @property bool       $completed
 * @property string     $method
 */
class InstructionEntity extends BaseEntity
{
    public function __construct($data)
    {
        if(isset($data['params'])) {
            if(is_string($data['params'])) {
                $data['params'] = unserialize($data['params']);
            }
        } else {
            $data['params'] = [];
        }

        parent::__construct($data);
    }

    /**
     * @return Result
     */
    public function run(): Result
    {
        list($method, $className) = explode('_', $this->method);
        $fullClassName = 'Orderbot\\Services\\' . ucfirst($className) . 'Service';
        $obj = new $fullClassName($this->params);
        $res = $obj->{$method}($this->params);
        $this->completed = true;

        return $res;
    }

    /**
     * @return InstructionStepEntity
     */
    private function getLastStep(): ?InstructionStepEntity
    {
        $res = null;
        $lastParamId = count($this->params) - 1;

        if ($lastParamId >= 0) {
            $name = array_keys($this->params)[$lastParamId];
            $res = InstructionStepModel::getByName($this->id, $name);
            if ($res->type == InstructionStepEntity::TYPE_NEXT) {
                $res->nextId = $this->params[$name];
            }
        }

        return $res;
    }

    /**
     * @return InstructionStepEntity|null
     */
    public function getNextStep(): ?InstructionStepEntity
    {
        $step = $this->getLastStep();
        if ($step) {
            return InstructionStepModel::getById($step->nextId);
        } else {
            return InstructionStepModel::getFirst($this->id);
        }
    }

    /**
     * @return int
     */
    public function saveAsLastInstruction(): int
    {
        $dataToSave = [
            'chat_id' => $this->chatId,
            'command_id' => $this->id,
            'params' => serialize($this->params),
            'created_at' => time(),
            'completed' => $this->completed,
        ];
        return LastInstructionModel::save($dataToSave);
    }

    /**
     * @param string $params
     */
    public function appendParamsFromString(string $params)
    {
        $paramList = unserialize($params);
        if(count($paramList)) {
            $this->params = array_merge($paramList, $this->params);
        }
    }

    /**
     * @param array $params
     */
    public function appendParams(array $params)
    {
        $this->params = array_merge($this->params, $params);
    }
}