<?php

namespace Meiosis\Models;

class BaseModel
{
    protected $data = [];
    protected $crmObject;

    public function __construct($data = [], $crmObject = null)
    {
        $this->populate((array) $data);
        $this->crmObject = $crmObject;
    }

    public static function getNativeFields()
    {
        return static::$native;
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

            $this->data[$safeKey] =  $item;
        }
    }

    /**
     * Extract the raw data array
     * @return array
     */
    public function extract()
    {
        return $this->data;
    }

    /**
     * Save our current instance
     * @return
     */
    public function save()
    {
        $new = $this->crmObject->save($this);
        $this->populate($new->extract());

        return $this;
    }

    /**
     * Reload the data from the api
     * @return BaseModel
     */
    public function refresh()
    {
        if (is_null($this->id)) {
            throw new ObjectNotPopulatedException('Can not refresh a customer that has not been saved');
        }

        $new = $this->crmObject->find($this->id);
        $this->populate($new->extract());

        return $this;
    }

    public function convertToKey($string)
    {
        $string = ucwords(str_replace(['-', '_'], ' ', $string));
        return lcfirst(str_replace(' ', '', $string));
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }
}
