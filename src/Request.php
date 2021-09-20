<?php

namespace Orderbot;

use TelegramBot\Api\Types\Update;

class Request
{
    const INSTRUCTION_FIELD = 'instruction';

    /** @var string */
    private $chatId = null;
    /** @var string */
    private $text = null;
    /** @var array */
    private $params = null;

    public function __construct(Update $update) {
        $message = $update->getMessage();
        $callback = $update->getCallbackQuery();

        if ($message) {
            $this->chatId = $message->getChat()->getId();
            $text = $message->getText();
            $this->text = $text;
        } else {
            $this->chatId = $callback->getFrom()->getId();
            $data = json_decode($callback->getData(), true);
            $this->params = $data;
        }
    }

    /**
     * @return null|string
     */
    public function extractText():?string
    {
        return $this->text;
    }

    /**
     * @return null|array
     */
    public function extractParams():?array
    {
        return $this->params;
    }

    /**
     * @return null|string
     */
    public function extractChatId():?string
    {
        return $this->chatId;
    }
}