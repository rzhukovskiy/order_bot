<?php

namespace Orderbot\Services;

use Orderbot\Entities\CommandEntity;
use Orderbot\Models\CommandModel;
use Orderbot\Models\CommandParamModel;
use Orderbot\Models\LastCommandModel;
use Orderbot\Result;

class CommandService
{
    private static $firstCommand = 'start';
    /**
     * @return Result
     */
    public static function handleText()
    {
        $commandText = isset($_GET['command']) ? $_GET['command'] : static::$firstCommand;
        $chatId = ChatService::getCurrentId();
        $currentUser = UserService::getCurrent();
        $res = null;

        $previousCommand = LastCommandModel::getByUser($chatId);
        $currentCommand = CommandModel::getByName($commandText);

        if ($previousCommand && !$previousCommand->completed) {
            $currentCommand = $previousCommand;
        }

        switch ($currentCommand->type) {
            case CommandEntity::TYPE_NAVIGATION:
                $res = new Result(
                    'navigation',
                    CommandModel::getByParentAndRole(
                        $currentCommand->id,
                        $currentUser->role
                    )
                );
                break;

            case CommandEntity::TYPE_EXECUTABLE:
                if (count($_POST)) {
                    $currentCommand->appendParams($_POST);
                }

                $nextParam = CommandParamModel::getNext(
                    $currentCommand->id,
                    $currentCommand->getLastParamOrder()
                );

                if ($nextParam) {
                    $res = new Result(
                        'param',
                        $nextParam
                    );
                } else {
                    $currentCommand->execute();
                    $res = new Result(
                        'navigation',
                        CommandModel::getByParentAndRole(
                            $currentCommand->parentId,
                            $currentUser->role
                        )
                    );
                }
                $currentCommand->saveAsLastCommand();
                break;
        }

        return $res;
    }


}