<?php

namespace Orderbot;

use Orderbot\Entities\InstructionEntity;
use Orderbot\Entities\InstructionStepEntity;
use TelegramBot\Api\BaseType;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Result
{
    const FIELD_MESSAGE = 'message';
    const FIELD_RESULT = 'result';
    const FIELD_STEP = 'step';
    const FIELD_MAIN = 'is_main';

    const ROW_SIZE = 3;

    /**
     * @var array
     */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @param Result $newResult
     */
    public function merge(Result $newResult)
    {
        foreach ($newResult->getData() as $key => $value) {
            if ($key == self::FIELD_MESSAGE) {
                $this->data[$key] .= "\n\n" . $value;
            } else {
                $this->data[$key] = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return mixed|null
     */
    public function getResult()
    {
        return $this->data[self::FIELD_RESULT] ?? null;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->data[self::FIELD_MESSAGE] ?? null;
    }

    /**
     * @return InstructionStepEntity|null
     */
    private function getStep(): ?InstructionStepEntity
    {
        return $this->data[self::FIELD_STEP] ?? null;
    }

    /**
     * @return bool
     */
    private function isMain(): bool
    {
        return $this->data[self::FIELD_MAIN] ?? false;
    }

    /**
     * @return ReplyKeyboardMarkup
     */
    public function getKeyboard(): ?BaseType
    {
        $step = $this->getStep();
        if ($step) {
            switch ($step->type) {
                case InstructionStepEntity::TYPE_LIST:
                case InstructionStepEntity::TYPE_NEXT:
                    $array = [[]];
                    $listButtons = json_decode($step->content, true);
                    foreach ($listButtons as $value => $description) {
                        $array[] = [[
                            'text' => $description,
                            'callback_data' => json_encode([
                                $step->name => $value,
                            ]),
                        ]];
                    }
                    return new InlineKeyboardMarkup($array);
                case InstructionStepEntity::TYPE_METHOD:
                    $result = $this->getResult();
                    if (!$result) {
                        return null;
                    }
                    $array = [[]];
                    foreach ($result as $item) {
                        $array[] = [[
                            'text' => $item->getDescription(),
                            'callback_data' => json_encode([
                                $step->name => $item->getAction(),
                            ]),
                        ]];
                    }
                    return new InlineKeyboardMarkup($array);
                default:
                    return null;
            }
        } else {
            /**
             * @var $result InstructionEntity[]
             */
            $result = $this->getResult();
            $array = [[]];
            if ($this->isMain()) {
                $rowNum = 0;
                for ($i = 0; $i < count($result); $i++) {
                    $array[$rowNum][] = $result[$i]->displayName;
                    if (($i + 1) % self::ROW_SIZE == 0) {
                        $rowNum++;
                    }
                }
                return new ReplyKeyboardMarkup($array, true, true);
            } else {
                $rowNum = 0;
                $i = 0;
                foreach ($result as $command) {
                    $array[$rowNum][] = [
                        'text' => $command->displayName,
                        'callback_data' => json_encode(['instruction' => $command->name]),
                    ];
                    if (($i + 1) % self::ROW_SIZE == 0) {
                        $rowNum++;
                    }
                    $i++;
                }
                return new InlineKeyboardMarkup($array);
            }
        }
    }
}