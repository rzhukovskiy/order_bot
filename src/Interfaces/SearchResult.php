<?php

namespace Orderbot\Interfaces;

interface SearchResult
{
    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getAction();
}