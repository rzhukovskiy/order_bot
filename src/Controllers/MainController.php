<?php

namespace Orderbot\Controllers;

use Orderbot\BaseController;
use Orderbot\Request;
use Orderbot\Services\CommandService;

class MainController extends BaseController
{
    public function actionIndex()
    {
        $result = CommandService::handleText(
            Request::extractCommand(),
            Request::extractParams()
        );

        $result->render();
    }
}