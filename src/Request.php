<?php

namespace Orderbot;

class Request
{
    private function __construct() {}

    /**
     * @return string
     */
    public static function extractCommand()
    {
        return $_GET['command'];
    }

    /**
     * @return array
     */
    public static  function extractParams()
    {
        return isset($_POST['params']) ? $_POST['params'] : [];
    }
}