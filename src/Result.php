<?php

namespace Orderbot;

use Orderbot\Entities\InstructionStepEntity;

class Result
{
    const FIELD_MESSAGE = 'message';
    const FIELD_RESULT  = 'result';
    const FIELD_STEP    = 'step';

    private static $paramViews = [
        InstructionStepEntity::TYPE_TEXT => 'text',
        InstructionStepEntity::TYPE_LIST => 'list',
        InstructionStepEntity::TYPE_METHOD => 'method',
        InstructionStepEntity::TYPE_NEXT => 'list',
    ];
    /**
     * @var array
     */
    private $data;
    /**
     * @var string
     */
    private $template = 'main';

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
            $this->data[$key] = $value;
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
    private function getMessage(): ?string
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
     *
     */
    public function render()
    {
        ob_start();

        $message = $this->getMessage();
        if ($message) {
            require 'views/message.php';
        }

        $step = $this->getStep();
        if ($step) {
            require 'views/' . self::$paramViews[$step->type] . '.php';
        } else {
            require 'views/navigation.php';
        }
        require 'views/footer.php';

        $content = ob_get_contents();
        ob_end_clean();

        require 'views/templates/' . $this->template . '.php';
    }
}