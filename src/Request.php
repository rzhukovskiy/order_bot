<?php

namespace Orderbot;

use Orderbot\Models\InstructionModel;
use TelegramBot\Api\Types\Update;

class Request
{
    /** @var string */
    private static $chatId = null;
    /** @var string */
    private static $instruction = null;
    /** @var array */
    private static $params = null;

    private function __construct() {}

    public static function init(Update $update) {
        $message = $update->getMessage();
        $callback = $update->getCallbackQuery();

        if ($message) {
            self::$chatId = $message->getChat()->getId();
            $instruction = $message->getText();
        } else {
            self::$chatId = $callback->getFrom()->getId();
            $data = json_decode($callback->getData(), true);
            $instruction = $data['instruction'] ?? null;
            unset($data['instruction']);
            self::$params = $data;
        }

        self::$instruction = $instruction;
    }

    /**
     * @return null|string
     */
    public static function extractText():?string
    {
        return self::$instruction;
    }

    /**
     * @return null|array
     */
    public static function extractParams():?array
    {
        return self::$params;
    }

    /**
     * @return null|string
     */
    public static function extractChatId():?string
    {
        return self::$chatId;
    }
}