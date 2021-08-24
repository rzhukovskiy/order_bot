<?php

namespace Orderbot\Controllers;

use Orderbot\BaseController;
use Orderbot\Services\CommandService;
use Orderbot\Services\ChatService;
use Orderbot\Services\UserService;

class MainController extends BaseController
{
    public function actionIndex()
    {
        $result = CommandService::handleText();

        $this->render($result->getTemplate(), [
            'result' => $result->getData(),
        ]);
    }
}