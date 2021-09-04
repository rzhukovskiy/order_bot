<?php

namespace Orderbot;

class BaseController
{
    protected $action       = null;
    protected $controller   = null;
    protected $template     = 'main';

    public function __construct($controller, $action)
    {
        $this->action       = $action;
        $this->controller   = $controller;
        $this->init();
    }

    public function init() {

    }

    public function redirect($destination, $data = null)
    {
        $query = $data ? '?' .urldecode(http_build_query($data)) : '';
        $url = 'Location: /' . ltrim($destination, '/') . $query;
        header($url);
        exit;
    }
}