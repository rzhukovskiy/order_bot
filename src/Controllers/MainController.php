<?php

namespace Orderbot\Controllers;

use Orderbot\BaseController;
use Orderbot\Entities\UserEntity;
use Orderbot\Models\UserModel;
use Orderbot\Request;
use Orderbot\Services\CommandService;
use Orderbot\Services\UserService;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\Update;

class MainController extends BaseController
{
    /**
     *
     */
    public function actionIndex()
    {
        try {
            $bot = new Client('');

            //Handle text messages
            $bot->on(function (Update $update) use ($bot) {
                $request = new Request($update);

                //Check user
                $user = UserModel::getByChatId($request->extractChatId());
                if (!$user) {
                    $user = new UserEntity([
                        'chat_id'   => $request->extractChatId(),
                        'name' => $request->getUserName(),
                        'role' => UserEntity::ROLE_BLOCKED,
                    ]);
                    $user->save();
                }
                UserService::setCurrentByChatId($user->chatId);
                if ($user->role == UserEntity::ROLE_BLOCKED) {
                    $bot->sendMessage(
                        $request->extractChatId(),
                        "Добрый день, {$user->name}. Пока что у тебя нет авторизации. Ждем ответа от админа",
                        null,
                        false,
                        null,
                        null
                    );

                    $array = [[]];
                    foreach (UserEntity::$roleNames as $value => $description) {
                        $array[] = [[
                            'text' => $description,
                            'callback_data' => json_encode([
                                'id'          => $user->id,
                                'instruction' => '/role_user',
                                'role'        => $value,
                            ]),
                        ]];
                    }
                    $userApproveKeyboard = new InlineKeyboardMarkup($array);
                    foreach (UserModel::getByRole(UserEntity::ROLE_ADMIN) as $admin) {
                        $bot->sendMessage(
                            $admin->chatId,
                            "Тут некто {$user->name} ломится в бота. Добавим?",
                            null,
                            false,
                            null,
                            $userApproveKeyboard
                        );
                    }
                } else {
                    $result = CommandService::handleRequest($request);

                    $bot->sendMessage(
                        $request->extractChatId(),
                        $result->getMessage(),
                        null,
                        false,
                        null,
                        $result->getKeyboard()
                    );
                }
            }, function () {
                return true;
            });

            $bot->run();
        } catch (Exception $e) {
            print_r($e);
        }
    }
}
