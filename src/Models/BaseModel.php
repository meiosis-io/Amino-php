<?php

namespace Meiosis\Models;

class BaseModel
{
    protected $rawData = [];

    public function __construct($data = [])
    {
        $this->populate((array) $data);
    }

    public function populate(array $data)
    {
        foreach ($data as $key => $item) {
            $safeKey = $this->convertToKey($key);
            $func = 'set'.strtoupper($safeKey);

            if (method_exists($this, $func)) {
                $this->{$func}($item);
                continue;
            }

            $this->rawData[$safeKey] =  $item;
        }
    }

    public function convertToKey($string)
    {
        $string = ucwords(str_replace(['-', '_'], ' ', $string));
        return lcfirst(str_replace(' ', '', $string));
    }

    public function __set($name, $value)
    {
        $this->rawData[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->rawData)) {
            return $this->rawData[$name];
        }

        return null;
    }
}
