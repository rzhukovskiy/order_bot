<?php

namespace Orderbot;

use Exception;

class Router
{
    private static $defaultController = 'main';
    private static $defaultAction = 'index';

    private function __construct()
    {
    }

    public static function handleRequest()
    {
        if (isset($_GET['r'])) {
            $uri = $_GET['r'];
            unset($_GET['r']);
            $query = http_build_query($_GET);
        } else {
            list($uri, $query) = array_pad(explode('?', $_SERVER['REQUEST_URI']), 2, null);
        }

        $_SERVER['REQUEST_URI'] = $query;
        list($controller, $action) = array_pad(explode('/', trim($uri, '/')), 2, null);
        if (empty($controller)) {
            $controller = static::$defaultController;
        }
        if (empty($action)) {
            $action = static::$defaultAction;
        }

        $controllerFull = 'Orderbot\\Controllers\\' . ucfirst($controller) . 'Controller';
        $actionFull     = 'action' . ucfirst($action);

        if (!class_exists($controllerFull) || !method_exists($controllerFull, $actionFull)) {
            http_response_code(404);
            die();
        }
        try {
            /** @var BaseController $controllerObject */
            $controllerObject = new $controllerFull($controller, $action);
            $controllerObject->$actionFull();
        } catch (Exception $e) {
            print_r($e);
            die;
        }
    }
}