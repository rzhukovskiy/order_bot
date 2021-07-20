<?php

use Orderbot\Router;

require '../vendor/autoload.php';
try {
    $router = Router::getInstance();
    $router->handleRequest();
} catch(Exception $ex) {
    print_r($ex);die;
}