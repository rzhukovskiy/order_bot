<?php

use Orderbot\BaseModel;
use Orderbot\Router;

require '../vendor/autoload.php';
try {
    BaseModel::init();
    Router::handleRequest();
} catch(Exception $ex) {
    print_r($ex);die;
}