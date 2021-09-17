<?php

namespace Orderbot\Controllers;

use Orderbot\BaseController;
use Orderbot\Request;
use Orderbot\Services\CommandService;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;
use TelegramBot\Api\Types\Update;

class MainController extends BaseController
{
    /**
     *
     */
    public function actionIndex()
    {
        try {
            $bot = new Client('1978286443:AAE67AlmMdcbCHMCIgZ-yEM-mcNsOJaAips');

            //Handle text messages
            $bot->on(function (Update $update) use ($bot) {
                Request::init($update);

                $result = CommandService::handleText(
                    Request::extractText(),
                    Request::extractParams()
                );

                $bot->sendMessage(
                    Request::extractChatId(),
                    $result->getMessage(),
                    null,
                    false,
                    null,
                    $result->getKeyboard()
                );
            }, function () {
                return true;
            });

            $bot->run();
        } catch (Exception $e) {
            print_r($e);
        }
    }
}