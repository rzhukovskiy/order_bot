<?php

namespace Orderbot;

class BaseEntity
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __get($name)
    {
        $name = $this->fromCamelCase($name);
        if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    public function __set($name, $value)
    {
        $name = $this->fromCamelCase($name);
        $this->data[$name] = $value;
    }

    public function deleteProperty($name)
    {
        $name = $this->fromCamelCase($name);
        unset($this->data[$name]);
    }

    /**
     * @param string $input
     * @return string
     */
    private function toCamelCase(string $input): string
    {
        return lcfirst(str_replace('_', '', ucwords($input, '_')));
    }

    /**
     * @param string $input
     * @return string
     */
    private function fromCamelCase(string $input): string
    {
        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all($pattern, $input, $matches);
        $res = $matches[0];
        foreach ($res as &$match) {
            $match = $match == strtoupper($match) ?
                strtolower($match) :
                lcfirst($match);
        }
        return implode('_', $res);
    }
}