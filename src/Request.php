<?php

namespace Orderbot;

use TelegramBot\Api\Types\Update;

class Request
{
    const INSTRUCTION_FIELD = 'instruction';

    /** @var string */
    private $chatId;
    /** @var string */
    private $text = null;
    /** @var array */
    private $params = null;
    /** @var string */
    private $username = null;

    public function __construct(Update $update) {
        $message = $update->getMessage();
        $callback = $update->getCallbackQuery();

        if ($message) {
            $this->chatId = $message->getChat()->getId();
            $text = $message->getText();
            $this->text = $text;
            $this->username = $message->getFrom()->getFirstName() . ' ' .
                $message->getFrom()->getLastName();
        } else {
            $this->chatId = $callback->getFrom()->getId();
            $data = json_decode($callback->getData(), true);
            $this->params = $data;
            $this->username = $callback->getFrom()->getFirstName() . ' ' .
                $callback->getFrom()->getLastName();
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

    /**
     * @return null|string
     */
    public function getUserName():?string
    {
        return $this->username;
    }
}