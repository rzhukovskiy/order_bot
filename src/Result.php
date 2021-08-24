<?php

namespace Orderbot;

class Result
{
    /**
     * @var string
     */
    private $template;
    private $data;

    public function __construct($template, $data)
    {
        $this->template = $template;
        $this->data     = $data;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getData()
    {
        return $this->data;
    }
}